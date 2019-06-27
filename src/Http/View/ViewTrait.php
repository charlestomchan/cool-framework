<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 14:57
 */

namespace Cool\Http\View;

use Cool\Http\View;

/**
 * Trait ViewTrait
 * @package Cool\Http\View
 */
trait ViewTrait
{

    /**
     * 渲染视图 (包含布局)
     * @param $name
     * @param array $data
     * @return string
     */
    public function render($name, $data = [])
    {
        if (strpos($name, '.') === false) {
            $name = View::prefix($this) . '.' . $name;
        }
        $layout          = $this->layout ?? 'main'; // use的类定义的layout属性会与Trait覆盖冲突，所以Trait内不可定义layout属性
        $view            = new View();
        $data['content'] = $view->render($name, $data);
        return $view->render("layouts.{$layout}", $data);
    }

    /**
     * 渲染视图 (不包含布局)
     * @param $name
     * @param array $data
     * @return string
     */
    public function renderPartial($name, $data = [])
    {
        if (strpos($name, '.') === false) {
            $name = View::prefix($this) . '.' . $name;
        }
        $view = new View();
        return $view->render($name, $data);
    }

}