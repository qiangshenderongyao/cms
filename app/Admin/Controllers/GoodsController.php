<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\edit;
use App\Model\GoodsModel;
use App\Model\UserModel;
class GoodsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('商品列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('添加')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoodsModel);
        $grid->model();
        $grid->goods_id('商品ID');
        $grid->goods_name('商品名称');
        $grid->store('商品库存');
        $grid->price('价格');
        $grid->create_at('添加时间')->display(function($time){
            return date($time); 
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(GoodsModel::findOrFail($id));

        $show->goods_id('商品ID');
        $show->goods_name('商品名称');
        $show->store('商品库存');
        $show->price('价格');
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GoodsModel);

        $form->display('goods_id', '商品ID');
        $form->text('goods_name', '商品名称');
        $form->number('store', '库存');
        $form->currency('price','价格')->symbol('￥');
        $form->ckeditor('content','简介');
        return $form;
    }
}