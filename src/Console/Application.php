<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 16:49
 */

namespace Cool\Console;

use Cool\Console\CommandLine\Arguments;
use Cool\Console\CommandLine\Flag;
use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Application as BaseApplication;
use Cool\Foundation\Coroutine;
use Cool\Foundation\Exceptions\CommandException;
use Cool\Foundation\Exceptions\NotFoundException;
use Cool\Support\FileSystem;

/**
 * Class Application
 * @package Cool\Console
 */
class Application extends BaseApplication
{

    /**
     * 应用名称
     * @var string
     */
    public $appName = 'app-console';

    /**
     * 应用版本
     * @var string
     */
    public $appVersion = '0.0.0';

    /**
     * 命令命名空间
     * @var string
     */
    public $commandNamespace = '';

    /**
     * 命令
     * @var array
     */
    public $commands = [];

    /**
     * 初始化事件
     */
    public function onInitialize()
    {
        parent::onInitialize(); // TODO: Change the autogenerated stub
        // 禁用内置协程
        Coroutine::disableBuiltin();
    }

    /**
     * 执行功能 (CLI模式)
     * @return mixed
     */
    public function run()
    {
        if (PHP_SAPI != 'cli') {
            throw new \RuntimeException('Please run in CLI mode.');
        }
        Flag::initialize();
        if (Arguments::subCommand() == '' && Arguments::command() == '') {
            if (Flag::bool(['h', 'help'], false)) {
                $this->help();
                return;
            }
            if (Flag::bool(['v', 'version'], false)) {
                $this->version();
                return;
            }
            $options = Flag::options();
            if (empty($options)) {
                $this->help();
                return;
            }
            $keys = array_keys($options);
            $flag = array_shift($keys);
            $script = Arguments::script();
            throw new NotFoundException("flag provided but not defined: '{$flag}', see '{$script} --help'.");
        }
        if ((Arguments::command() !== '' || Arguments::subCommand() !== '') && Flag::bool(['h', 'help'], false)) {
            $this->commandHelp();
            return;
        }
        $command = trim(implode(' ', [Arguments::command(), Arguments::subCommand()]));
        return $this->runAction($command);
    }

    /**
     * 帮助
     */
    protected function help()
    {
        $script = Arguments::script();
        println("Usage: {$script} [OPTIONS] COMMAND [SUBCOMMAND] [arg...]");
        $this->printOptions();
        $this->printCommands();
        println('');
        println("Run '{$script} COMMAND [SUBCOMMAND] --help' for more information on a command.");
        println('');
        println("Developed with Cool PHP framework.");
    }

    /**
     * 命令帮助
     */
    protected function commandHelp()
    {
        $script = Arguments::script();
        $command = trim(implode(' ', [Arguments::command(), Arguments::subCommand()]));
        println("Usage: {$script} {$command} [arg...]");
        $this->printCommandOptions();
        println("Developed with Cool PHP framework");
    }

    /**
     * 版本
     */
    protected function version()
    {
        $appName = \Cool::$app->appName;
        $appVersion = \Cool::$app->appVersion;
        $frameworkVersion = \Cool::$version;
        println("{$appName} version {$appVersion}, framework version {$frameworkVersion}");
    }

    /**
     * 打印选项列表
     */
    protected function printOptions()
    {
        $tabs = $this->hasSubCommand() ? "\t\t" : "\t";
        println('');
        println('Options:');
        println("  -h, --help{$tabs}Print usage.");
        println("  -v, --version{$tabs}Print version information.");
    }

    /**
     * 有子命令
     * @return bool
     */
    protected function hasSubCommand()
    {
        foreach ($this->commands as $key => $item) {
            if (strpos($key, ' ') !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * 打印命令列表
     */
    protected function printCommands()
    {
        println('');
        println('Commands:');
        foreach ($this->commands as $key => $item) {
            $command = $key;
            $subCommand = '';
            $description = $item['description'] ?? '';
            if (strpos($key, ' ') !== false) {
                list($command, $subCommand) = explode(' ', $key);
            }
            if ($subCommand == '') {
                println("    {$command}\t{$description}");
            } else {
                println("    {$command} {$subCommand}\t{$description}");
            }
        }
    }

    /**
     * 打印命令选项列表
     */
    protected function printCommandOptions()
    {
        $command = trim(implode(' ', [Arguments::command(), Arguments::subCommand()]));
        if (!isset($this->commands[$command]['options'])) {
            return;
        }
        $options = $this->commands[$command]['options'];
        println('');
        println('Options:');
        foreach ($options as $option) {
            $names = array_shift($option);
            if (is_string($names)) {
                $names = [$names];
            }
            $flags = [];
            foreach ($names as $name) {
                if (strlen($name) == 1) {
                    $flags[] = "-{$name}";
                } else {
                    $flags[] = "--{$name}";
                }
            }
            $flag = implode(', ', $flags);
            $description = $option['description'] ?? '';
            println("  {$flag}\t{$description}");
        }
        println('');
    }

    /**
     * 执行功能并返回
     * @param $command
     * @return mixed
     */
    public function runAction($command)
    {
        if (!isset($this->commands[$command])) {
            $script = Arguments::script();
            throw new NotFoundException("'{$command}' is not command, see '{$script} --help'.");
        }
        // 实例化控制器
        $shortClass = $this->commands[$command];
        if (is_array($shortClass)) {
            $shortClass = array_shift($shortClass);
        }
        $shortClass = str_replace('/', "\\", $shortClass);
        $commandDir = FileSystem::dirname($shortClass);
        $commandDir = $commandDir == '.' ? '' : "$commandDir\\";
        $commandName = FileSystem::basename($shortClass);
        $commandClass = "{$this->commandNamespace}\\{$commandDir}{$commandName}Command";
        $commandAction = 'main';
        // 判断类是否存在
        if (!class_exists($commandClass)) {
            throw new CommandException("'{$commandClass}' class not found.");
        }
        $commandInstance = new $commandClass();
        // 判断方法是否存在
        if (!method_exists($commandInstance, $commandAction)) {
            throw new CommandException("'{$commandClass}::main' method not found.");
        }
        // 命令行选项效验
        $this->validateOptions($command);
        // 执行方法
        return call_user_func([$commandInstance, $commandAction]);
    }

    /**
     * 命令行选项效验
     * @param $command
     */
    protected function validateOptions($command)
    {
        $options = $this->commands[$command]['options'] ?? [];
        $regflags = [];
        foreach ($options as $option) {
            $names = array_shift($option);
            if (is_string($names)) {
                $names = [$names];
            }
            foreach ($names as $name) {
                if (strlen($name) == 1) {
                    $regflags[] = "-{$name}";
                } else {
                    $regflags[] = "--{$name}";
                }
            }
        }
        foreach (array_keys(Flag::options()) as $flag) {
            if (!in_array($flag, $regflags)) {
                $script = Arguments::script();
                $command = Arguments::command();
                $subCommand = Arguments::subCommand();
                $fullCommand = $command . ($subCommand ? " {$subCommand}" : '');
                throw new NotFoundException("flag provided but not defined: '{$flag}', see '{$script} {$fullCommand} --help'.");
            }
        }
    }

    /**
     * 获取组件
     * @param $name
     * @return ComponentInterface
     */
    public function __get($name)
    {
        // 从容器返回组件
        return $this->container->get($name);
    }

}