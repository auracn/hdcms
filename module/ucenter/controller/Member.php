<?php namespace module\ucenter\controller;

/**
 * 会员中心
 * Class Member
 *
 * @package module\ucenter\controller
 */
use Db;
use houdunwang\view\View;

class Member extends Auth
{
    /**
     * 会员中心
     *
     * @return mixed
     */
    public function index()
    {
        //读取菜单
//        $where  = [['entry', 'member'], ['siteid', SITEID]];
//        $module = Db::table('navigate')->where($where)->groupBy('module')->lists('module');
//        $data   = [];
//        foreach ($module as $m) {
//            if ($moduleData = Db::table('modules')->where('name', $m)->first()) {
//                $where  = [['entry', 'member'], ['siteid', SITEID], ['module', $m]];
//                $data[] = [
//                    'module' => $moduleData,
//                    'menus'  => Db::table('navigate')->where($where)->get(),
//                ];
//            }
//        }
//        foreach ($data as $k => $v) {
//            foreach ($v['menus'] as $n => $m) {
//                $data[$k]['menus'][$n]['css'] = json_decode($m['css'], true);
//            }
//        }
        //昵称检测
        $nickStatus = v('member.info.nickname') == '' || v('member.info.nickname') == '幸福小海豚';
        if (v('site.setting.config.must_set_nickname') && $nickStatus) {
            return message('会员昵称没有设置', url('my.info', [], 'ucenter'));
        }
        //头像检测
        if (v('site.setting.config.must_set_icon') && v('member.info.icon') == '') {
            return message('请先设置会员头像后操作', url('my.info', [], 'ucenter'));
        }
        //移动端设置
        if (IS_MOBILE) {
            //会员中心信息
            $where   = [['siteid', siteid()], ['type', 'profile']];
            $ucenter = Db::table('page')->where($where)->pluck('params');
            $ucenter = json_decode($ucenter, true);
            View::with('ucenter', $ucenter);
        }

        return $this->view($this->template.'/index', compact('data'));
    }
}