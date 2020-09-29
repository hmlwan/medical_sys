<?php
/**
 * Created by PhpStorm.
 * User: hmlwan521
 * Date: 2020/9/5
 * Time: 下午6:00
 */
namespace app\admin\controller;

use think\Db;
use think\facade\Session;
use think\Request;

class TypeIn extends Admin{

    protected function initialize($dispatch){
        parent::initialize();
    }
    /**
     * 列表
     */
    public  function index(Request $request){
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        $p = $request->get('p',1);
        $page_num = $request->get('page_num',10);

        $card_id = $request->get('card_id');
        $check_number = $request->get('check_number');
        $real_name = $request->get('real_name');
        $searche_abridge = $request->get('searche_abridge');
        $date1 = $request->get('date1','');
        $date2 = $request->get('date2','');

        $offset = ($p-1) * $page_num;
        $where = array();
        $where['is_finish'] = 0;
        if($AUTH_TYPE == 2){
            $where['reguid'] = $USER_KEY_ID;
        }
        if($card_id){
            $where['card_id'] = $card_id;
        }
        if($check_number){
            $where['check_number'] = $check_number;
        }
        if($real_name){
            $where['real_name'] = $real_name;
        }
        if($searche_abridge){
            $where['abridge'] = $searche_abridge;
        }
        if($date1 && $date2){
            $where[] = array(
                'create_time','between',array(($date1." 00:00:00"),($date1.' 23:59:59'))
            );
        }elseif (!$date1 && $date2){
            $where[] = array(
                'create_time','elt',($date2.' 23:59:59')
            );
        }elseif ($date1 && !$date2){
            $where[] = array(
                'create_time','egt',($date1.' 00:00:00')
            );
        }

        $total =  Db::table('user')->where($where)->count();

        $list = Db::table('user')->where($where)->limit($offset,$page_num)->order('id desc')->select();
        $data = array(
            'total' => $total,
            'list' => $list,
        );
        return json()->data(['code'=>0,'data'=>$data]);
    }
    /**
     * 编辑
     */
    public function edit(Request $request){

        $id = $request->post('id');
        $card_id = $request->post('card_id');
        $real_name = $request->post('real_name');
        $age = $request->post('age');
        $sex = $request->post('sex');
        $address = $request->post('address');
        $mobile = $request->post('mobile');
        $avatar = $request->post('avatar');
        $sign_img = $request->post('sign_img');
        $abridge = $request->post('abridge');

        $USER_KEY_ID = Session::get('USER_KEY_ID');

        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != 2){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        if(!$abridge){
            return json()->data(['code'=>1,'message'=>'请选择机构']);
        }
        if(!$card_id || !$real_name){
             return json()->data(['code'=>1,'message'=>'缺少参数']);
        }
        $manager_id = Db::table('manage_user')->where('id','=',$USER_KEY_ID)->value('pid');
        if(!$manager_id){
            return json()->data(['code'=>1,'message'=>'未知错误']);
        }
        $k_w[] = ['manager_id','=',$manager_id];
        $k_w[] = array('create_time','between',array(date("Y-m-d 0:0:0",time()),date("Y-m-d 23:59:59",time())));

        $k = Db::table('user')->where($k_w)->count();
        $k = $k+1;
        $k = sprintf("%03d",$k);
        $sub_data = array(
            'abridge' => $abridge,
            'card_id' => $card_id,
            'address' => $address,
            'mobile' => $mobile,
            'real_name' => $real_name,
            'age' => $age,
            'sex' => $sex,
            'avatar' => $avatar,
            'sign_img' => $sign_img,
            'manager_id' => $manager_id,
            'reguid' => $USER_KEY_ID,
            'is_type_in' => 1,
            'check_number' => $abridge.date("ymd",time()).$k,
            'create_time' => date("Y-m-d H:i:s",time())
        );

        if($id){ //编辑
            $r = Db::table('user')->where('id','=',$id)->update($sub_data);

        }else{//新增

            $r = Db::table('user')->insert($sub_data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);
    }
    public function del(Request $request){
        $id = $request->post('id');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if(!$id){
            return json()->data(['code'=>1,'message'=>'未知错误']);
        }
        if($AUTH_TYPE != 2){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        $r = Db::table('user')->delete($id);
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);
    }
    public function get_data(Request $request){
        $project = $request->post('project');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        $p = $request->post('p',1);
        $page_num = $request->post('page_num',10);
        $offset = ($p-1) * $page_num;

        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $field = '';
        $u_auth_type = '';
        switch ($project){
            case 'comm': //一般检查
                $u_auth_type = 3;
                $field = '';
                $search_field = 'is_com';
                break;
            case 'electrocardiogram': //心电图
                $u_auth_type = 4;
                $field = '';
                $search_field = 'is_ecg';
                break;
            case 'qc': //检验
                $u_auth_type = 7;
                $field = '';
                $search_field = 'is_qc';
                break;
            case 'check':
                $u_auth_type = 10;
                $field = '';
                break;
        }
        if($AUTH_TYPE != $u_auth_type){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }

        $manager_id = Db::table('manage_user')->where('id','=',$USER_KEY_ID)->value('pid');

        $where = array(
            'manager_id' => $manager_id,
            'is_finish' => 0
        );
        $card_id = $request->post('card_id');
        $check_number = $request->post('check_number');
        $date1 = $request->post('date1');
        $date2 = $request->post('date2');
        $check_status = $request->post('check_status');
        $real_name = $request->post('real_name');
        $sex = $request->post('sex');
        $abridge = $request->post('abridge');
        if($card_id){
            $where['card_id'] = $card_id;
        }
        if($check_number){
            $where['check_number'] = $check_number;
        }
        if($real_name){
            $where['real_name'] = $real_name;
        }
        if($check_status != ''){
            $where[$search_field] = $check_status;
        }
        if($abridge != ''){
            $where['abridge'] = $abridge;
        }
        if($sex){
            $where['sex'] = $sex;
        }
        if($date1 && $date2){
            $where[] = array(
                'create_time','between',array(($date1." 00:00:00"),($date1.' 23:59:59'))
            );
        }elseif (!$date1 && $date2){
            $where[] = array(
                'create_time','elt',($date2.' 23:59:59')
            );
        }elseif ($date1 && !$date2){
            $where[] = array(
                'create_time','egt',($date1.' 00:00:00')
            );
        }
        $total =  Db::table('user')->where($where)->count();
        $list =  Db::table('user')->where($where)->limit($offset,$page_num)->field($field)->select();
        $data = array(
            'total' => $total,
            'list' => $list,
        );
        return json()->data(['code'=>0,'message'=>'成功','data'=>$data]);
    }
    public function get_data_info(Request $request){
        $id = $request->post('id');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        $USER_KEY_ID = Session::get('USER_KEY_ID');

        $project = $request->post('project');
        if(!$id){
            return json()->data(['code'=>1,'message'=>'未知错误']);
        }
        $field = '';
        $u_auth_type = '';
        switch ($project){
            case 'comm':
                $u_auth_type = 3;
                $table = 'user_com';
                break;
            case 'electrocardiogram':
                $u_auth_type = 4;
                $table = 'user_ecg';
                break;
            case 'qc':
                $u_auth_type = 7;
                $table = 'user_qc';
                break;
            case 'check':
                $u_auth_type = 10;
                $table = 'user';
                break;
        }
        if($AUTH_TYPE != $u_auth_type){
            return json()->data(['code'=>1  ,'message'=>'无操作权限']);
        }
        if($project == 'check'){
            $info = Db::table($table)->where('id','=',$id)->field($field)->find();
            if($info){
                $com = Db::table('user_com')->where('uid','=',$id)->find();
                $xdt = Db::table('user_ecg')->where('uid','=',$id)->find();
                $qc = Db::table('user_qc')->where('uid','=',$id)->find();
                $info['com_data'] = $com;
                $info['xdt_data'] = $xdt;
                $info['qc_data'] = $qc;
                $message_auto = array();
                if($com){ //一般检查
                    $com_mess_auto = json_decode($com['message_auto'],true);
                    if($com_mess_auto){
                        $message_auto = array_merge($message_auto,$com_mess_auto);
                    }
                }
                if($xdt){ //心电图
                    $xdt_mess_auto = json_decode($xdt['message_auto'],true);
                    if($xdt_mess_auto){
                        $message_auto = array_merge($message_auto,array('xdt'=>$xdt_mess_auto));
                    }
                }
                if($qc){ //检验
                    $qc_mess_auto = json_decode($qc['message_auto'],true);
                    if($qc_mess_auto){
                        $message_auto = array_merge($message_auto,$qc_mess_auto);
                    }
                }
                $info['message_auto'] =  $message_auto;
            }
        }else{
            $info = Db::table($table)->where('uid','=',$id)->field($field)->find();
        }
        return json()->data(['code'=>0,'message'=>'成功','data'=>$info?$info:array()]);
    }
    /*添加一般检查*/
    public function com_edit(Request $request){
        $id = $request->post('id');
        $data = $request->post('');
        $sg = $request->post('sg');
        $tz = $request->post('tz');

        $AUTH_TYPE = Session::get('AUTH_TYPE');
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        if($AUTH_TYPE != 3){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        if(!$sg){
            return json()->data(['code'=>1,'message'=>'请输入身高']);
        }
        if(!$tz){
            return json()->data(['code'=>1,'message'=>'请输入体重']);
        }
        $info = Db::table('user_com')->where('uid','=',$id)->value('id');

        if(!$id){
            return json()->data(['code'=>1,'message'=>'未知错误']);
        }
        //bmi小结
        $bmi =  trim($data['bmi']);
        $message = '';
        $bmi_str = '';
        $bmi_lable = '';
        $bmi_popularization = '';
        $bmi_propose = '';

        if($bmi){
            //bmi数据
            $bmi_info = Db::table('basic_conf')->where('label','=','BMI')->find();

            if($bmi_info){
                $bmi_detail_list =  Db::table('basic_detail_conf')->where('basic_id','=',$bmi_info['id'])->select();

                if($bmi_detail_list){
                    foreach ($bmi_detail_list as $bk => $bv){
                        $bv_operator = $bv['operator'];
                        $bv_ranges = trim($bv['ranges']);
                        switch ($bv_operator){
                            case '1':
                              if($bmi <= $bv_ranges){
                                  $bmi_str = $bv['name'];
                                  $bmi_lable = $bv['label'];
                                  $bmi_popularization = $bv['popularization'];
                                  $bmi_propose = $bv['propose'];
                              }
                              break;
                            case '2':
                                $bv_ranges_arr = explode("~",$bv_ranges);

                                if($bmi >= trim($bv_ranges_arr[0]) && $bmi <= trim($bv_ranges_arr[1])){
                                    $bmi_str = $bv['name'];
                                    $bmi_lable = $bv['label'];
                                    $bmi_popularization = $bv['popularization'];
                                    $bmi_propose = $bv['propose'];
                                }
                                break;
                            case '3':
                                if($bmi >= $bv_ranges){
                                    $bmi_str = $bv['name'];
                                    $bmi_lable = $bv['label'];
                                    $bmi_popularization = $bv['popularization'];
                                    $bmi_propose = $bv['propose'];
                                }
                                break;
                        }
                    }
                }
            }
        }
        if($bmi_str){
            $message = "<p>BMI：{$bmi_str}</p>";
        }
        $message_auto = array();
        if($bmi_lable != 'normal'){
            $message_auto['bmi'] = array(
                'litefield' => 'bmi',
                'name' => $bmi_str,
                'popularization'=> $bmi_popularization,
                'propose'=> $bmi_propose,
                'title'=> $bmi
            );
        }
        //血压小结
        $bp_l = trim($data['zcxyss']);
        $bp_r = trim($data['zcxysz']);
        $bp_lable = '';
        $bp_popularization = '';
        $bp_propose = '';
        $bp_str = '';
        if($bp_l){
            //bmi数据
            $bp_info = Db::table('basic_conf')->where('label','=','BP')->find();
            if($bp_info){
                $bp_detail_list =  Db::table('basic_detail_conf')->where('basic_id','=',$bp_info['id'])->select();
                if($bp_detail_list){
                    foreach ($bp_detail_list as $pk => $pv){

                        $pv_operator = $pv['operator'];
                        $pv_ranges = $pv['ranges'];
                        $pv_arr = explode('&',$pv_ranges);
                        $pv_arr1 = trim($pv_arr[0]);
                        $pv_arr2 = trim($pv_arr[1]);
                        switch ($pv_operator){
                            case '1':
                                if($bp_l < $pv_arr1  && $bp_r < $pv_arr2  ){
                                    $bp_str = $pv['name'];
                                    $bp_lable = $pv['label'];
                                    $bp_popularization = $pv['popularization'];
                                    $bp_propose = $pv['propose'];
                                }
                                break;
                            case '3':
                                if($bp_l >= $pv_arr1  && $bp_r >= $pv_arr2  ){
                                    $bp_str = $pv['name'];
                                    $bp_lable = $pv['label'];
                                    $bp_popularization = $pv['popularization'];
                                    $bp_propose = $pv['propose'];
                                }
                                break;
                            case '4':
                                if($bp_l >= $pv_arr1 &&  $bp_r <$pv_arr2){
                                    $bp_str = $pv['name'];
                                    $bp_lable = $pv['label'];
                                    $bp_popularization = $pv['popularization'];
                                    $bp_propose = $pv['propose'];
                                }
                                break;
                        }
                    }
                }
            }
        }
        if($bp_str){
            $message = $message."<p>血压：{$bp_str}</p>";
        }
        if($bp_lable != 'normal'){
            $message_auto['dp'] = array(
                'litefield' => 'dp',
                'name' => $bp_str,
                'popularization'=> $bp_popularization,
                'propose'=> $bp_propose,
                'title'=> $bp_l
            );
        }
        //总结
        $data['message'] = $message;
        $data['message_auto'] = json_encode($message_auto,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        if($info>0){
           $r =  Db::table('user_com')->where('uid','=',$id)->update($data);
        }else{
           $data['uid'] = $id;
           $r =  Db::table('user_com')->insert($data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        Db::table('user')->where('id','=',$id)->update(array(
            'is_com' => 1,
            'comuid' => $USER_KEY_ID,
        ));
        return json()->data(['code'=>0,'message'=>'成功']);
    }

    /*添加检验*/
    public function qc_edit(Request $request){

        $id = $request->post('id');
        $sub_data = $request->post('');

        $xcg_part_ids = $sub_data['xcg_part_ids'];
        $ncg_part_ids = $sub_data['ncg_part_ids'];
        $sh_part_ids = $sub_data['sh_part_ids'];
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        if($AUTH_TYPE != 7){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        $info = Db::table('user_qc')->where('uid','=',$id)->value('id');

        if(!$id){
            return json()->data(['code'=>1,'message'=>'未知错误']);
        }
        $message_auto = $message = $xcg_data_arr = $ncg_data_arr = $sh_data_arr =array();
        //血常规
        if($xcg_part_ids){
            $x_data = $this->get_qc_res($xcg_part_ids);
            $x_message = $x_data['message'];
            $xcg_data_arr = $x_data['data_arr'];
        }
        //尿常规
        if($ncg_part_ids){
            $n_data = $this->get_qc_res($ncg_part_ids);
            $n_message = $n_data['message'];
            $ncg_data_arr = $n_data['data_arr'];
        }
        //生化
        if($sh_part_ids){
            $s_data = $this->get_qc_res($sh_part_ids);
            $s_message = $s_data['message'];
            $sh_data_arr = $s_data['data_arr'];
        }
        $message_auto = array(
            'xcg_data' => $xcg_data_arr,
            'ncg_data' => $ncg_data_arr,
            'sh_data' => $sh_data_arr,
        );
        $message = array(
            'xcg_message' => $x_message,
            'ncg_message' => $n_message,
            'sh_message' => $s_message,
        );
        $data = array(
            'xcg_data'=> $xcg_part_ids?json_encode($xcg_part_ids,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES):'',
            'ncg_data'=> $ncg_part_ids?json_encode($ncg_part_ids,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES):"",
            'sh_data'=> $sh_part_ids?json_encode($sh_part_ids,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES):"",
            'message' => json_encode($message,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
            'message_auto' => json_encode($message_auto,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
        );
        if($info>0){
            $r =  Db::table('user_qc')->where('uid','=',$id)->update($data);
        }else{
            $data['uid'] = $id;
            $r =  Db::table('user_qc')->insert($data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        Db::table('user')->where('id','=',$id)->update(array(
            'is_qc' => 1,
            'qcuid' => $USER_KEY_ID,
        ));
        return json()->data(['code'=>0,'message'=>'成功']);

    }
    public function get_qc_res($part_ids){
        $data_arr = array();
        $message = $g_str = '';
        foreach ($part_ids as $k => $v){
            $info = Db::table('measur')->where('id','=',$v['id'])->find();
            if($info){
                $ranges = $info['ranges'];
                $res = '';
                if($ranges){
                    $ranges_arr = explode('-',$ranges);
                    $is_normal = 1;
                    if($v['value'] < $ranges_arr[0]){
                        $res = '偏小';
                        $is_normal = 0;
                    }elseif($v['value'] >= $ranges_arr[0] && $v['value'] <= $ranges_arr[1]){
                        $res = '正常';
                    }else{
                        $res = '偏大';
                        $is_normal = 0;
                    }
                }else{
                    $res = '正常';
                }
                $g_str = "<p>{$info['name']}({$info['litefield']})：{$v['value']}{$info['unit']}，诊断结果：{$res}</p>";
                $message .= $g_str;
                if($is_normal == 0){
                    $data_arr[] = array(
                        'litefield' => $info['litefield'],
                        'name' => $info['name'],
                        'title' => $res,
                        'popularization' => $info['popularization'],
                        'propose' => $info['propose'],
                    );
                }
            }
        }
        return array(
            'message' => $message,
            'data_arr' => $data_arr,
        );
    }
    /*添加心电图*/
    public function ecg_edit(Request $request){
        $id = $request->post('id');
        $params = $request->post('');
        $xinlv = $request->post('xinlv');
        $zayin = $request->post('zayin');
        $diagnosis = $params['diagnosis'];

        $AUTH_TYPE = Session::get('AUTH_TYPE');
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        if($AUTH_TYPE != 4){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        $info = Db::table('user_ecg')->where('uid','=',$id)->value('id');

        if(!$id){
            return json()->data(['code'=>1,'message'=>'未知错误']);
        }
        $manager_id = Db::table('manage_user')->where('id','=',$USER_KEY_ID)->value('pid');

        $message = '';
        $popularization = '';
        $p_xinlv_str = '';
        $xinlv_str = "心率：";
        if($xinlv == 1 ){
            $xinlv_str .= '不齐';
            $p_xinlv_str =  '心率不齐';
        }else if($xinlv == 2){
            $xinlv_str .= '绝对不齐';
            $p_xinlv_str =  '心率绝对不齐';
        }else{
            $xinlv_str .= '齐';
        }
        $message .= "<p>".$xinlv_str."</p>";
        if($xinlv != 0){
            $popularization = $p_xinlv_str;
        }
        $zayin_str = "杂音：";
        $p_zayin_str = '';
        if($zayin == 1 ){
            $zayin_str .= '有';
            $p_zayin_str = '有杂音';
        }else{
            $zayin_str .= '无';
        }
        if($zayin != 0){
            $popularization .= "、{$p_zayin_str}";
        }
        $message .= "<p>".$zayin_str."</p>";
        $zhenduan_arr = [];
        $propose_arr = [];
        $zhenduan_str = '';
        $propose_str = '';

        if($diagnosis){
            foreach ($diagnosis as $template_id){
                $temp_info = $this->get_popularization($template_id,3,$manager_id);
                $zhenduan_arr[]  = $temp_info['popularization'];
                $propose_arr[]  = $temp_info['propose'];
            }
            $zhenduan_str = implode('、',$zhenduan_arr);
            $propose_str = implode('、',$propose_arr);
        }
        if($zhenduan_str){
            $zhenduan_str1 = "诊断：".$zhenduan_str;
            $message .= "<p>".$zhenduan_str1."</p>";
            $popularization .= "、{$zhenduan_str}";
        }
        $message_auto = array(
            'litefield' => 'ecg',
            'name' => '心电异常',
            'popularization'=> (trim($popularization,'、')),
            'propose'=> (trim($propose_str,'、')),
            'title'=> '心电异常'
        );

        $data = array(
            'xinlv' => $xinlv,
            'zayin' => $zayin,
            'diagnosis' => json_encode($diagnosis),
            'message' => (trim($message,'、')),
            'message_auto' => json_encode($message_auto,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)
        );

        if($info>0){
            $r =  Db::table('user_ecg')->where('uid','=',$id)->update($data);
        }else{
            $data['uid'] = $id;
            $r =  Db::table('user_ecg')->insert($data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        Db::table('user')->where('id','=',$id)->update(array(
            'is_ecg' => 1,
            'ecguid' => $USER_KEY_ID,
        ));
        return json()->data(['code'=>0,'message'=>'成功']);

    }

    public function get_popularization($template_id,$stype,$manager_id){
        $info = Db::table('template_agency')
            ->where(array(
                    'template_id'=> $template_id,
                    'stype' =>$stype,
                    'manager_id'=>$manager_id)
            )
            ->find();

        if(!$info){
            $info = Db::table('template')
                ->where(array(
                        'id'=> $template_id,
                        'stype' =>$stype)
                )
                ->find();
        }
        return $info;
    }

}