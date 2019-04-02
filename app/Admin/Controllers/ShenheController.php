<?php

namespace App\Admin\Controllers;

use App\Model\KsModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ShenheController extends Controller
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
            ->header('Index')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new KsModel);

        $grid->id('id');
        $grid->sname('sname');
        $grid->shenfen('shenfen');
        $grid->yt('yt');
        $grid->status('status');

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
        $show = new Show(KsModel::findOrFail($id));

        $show->id('id');
        $show->sname('sname');
        $show->shenfen('shenfen');
        $show->yt('yt');
        $show->status('status');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new KsModel());

        $form->id('id');
        $form->text('sname');
        $form->text('yt');
        $form->number('status');

        return $form;
    }
    public function fafang(){
        $model=new KsModel();
        $app_key=$model->app_key();
        $app_secret=$model->app_secret();
        $where=[
          'app_key'=>$app_key,
          'app_secret'=>$app_secret
        ];
//        var_dump($where);die;
        $data=KsModel::update($where);
        if($data){
            return '修改成功';
        }
    }
}