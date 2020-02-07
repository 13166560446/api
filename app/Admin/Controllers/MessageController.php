<?php

namespace App\Admin\Controllers;

use App\Model\MessageModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\MessageModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MessageModel);

        $grid->column('id', __('Id'));
        $grid->column('desc', __('Desc'));
        $grid->column('created_at', __('添加时间'));
        $grid->column('nickname', __('昵称'));
        $grid->column('headimgurl', __('头像'))->display(function($img){
            return '<img src="'.$img.'">';
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(MessageModel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('desc', __('Desc'));
        $show->field('created_at', __('Created at'));
        $show->field('nickname', __('Nickname'));
        $show->field('headimgurl', __('Headimgurl'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MessageModel);

        $form->text('desc', __('Desc'));
        $form->text('nickname', __('Nickname'));
        $form->text('headimgurl', __('Headimgurl'));

        return $form;
    }
}
