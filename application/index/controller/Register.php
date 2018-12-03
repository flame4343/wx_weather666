<?php
namespace app\index\controller;

use think\Controller;

class Register extends Controller
{
    public function register()
    {
    	return $this->fetch();
    }
    public function doRegister()
    {
      $param = input('post.');
		if(empty($param['user_name'])){
        	$this->error('用户名不能为空');
        }
      
    	if(empty($param['user_pwd'])){
        	$this->error('密码不能为空');
        }
      
        //验证用户名
      	$has = db('users')->where('user_name', $param['user_name'])->find();
      	if(!empty($has)){
          	$this->error('用户名已存在');
        }
      	$data = [		
				'user_name' => input('user_name'),
				'user_pwd' => md5(input('user_pwd')),
			];
      	if(db('users') -> insert($data)){		//添加数据
				return $this->success('添加成功','login/index');
			}else{
				return $this->error('添加管理员失败');
			}
    }
}