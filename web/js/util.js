/**
 * [名称] API 引擎
 * [描述]
 *  基于axios的ajax二次封装，实现了基本的用户与服务器的数据交互。
 * [主要方法]
 *
 * [使用说明]
 * API.get();
 *
 * [依赖文件]: util.js,axios.js,qs.js,config.js
 * [创建日期]: 2017-04-07
 * [作者]: luobw
 * [版本]: v1.0
 */

;
(function() {
    var config = {
        url: '',
        baseURL: '',
        method: 'POST',
        transformRequest: [
            function(data) {
                //由于使用的form-data传数据所以要格式化
                // data = Util.JsonHtmlEncode(data,!data.notrim);
                data = Qs.stringify(data);
                return data;
            }
        ],
        transformResponse: [
            function(data) {
                if (data.statusCode === '120005') {
                    location.href ='login.html?returnurl='+location.href;
                    // var r = confirm('请重新登录~');
                    // r && API.post({
                    //     api: 'http://api.youjianwu.com/Guest/login', // ?a=get_users&uid=10001
                    //     param: { name: 'luobowen', password: 'abc12345' }
                    // }).then(function(res) {
                    //     if (res.statusCode === '000000') {
                    //         localStorage.setItem("auth", res.userInfo.auth);
                    //         delete res.userInfo.auth;
                    //         localStorage.setItem("user", JSON.stringify(res.userInfo));
                    //     }
                    // });


                }
                return data;
            }
        ],
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Token': localStorage.getItem("auth")
            // 'Access-Control-Allow-Origin':'*' 
        },
        params: {},
        paramsSerializer: function(params) {
            return Qs.stringify(params)
        },
        data: {},
        timeout: 1800000,
        withCredentials: false, // default
        responseType: 'json', // default
        //将upload事件注释掉，防止跨域状态下发起option请求
        // onUploadProgress: function(progressEvent) {
        //  // Do whatever you want with the native progress event
        // },
        // onDownloadProgress: function(progressEvent) {
        //  // Do whatever you want with the native progress event
        // },

        maxContentLength: 2000,
        validateStatus: function(status) {
            return status >= 200 && status < 300; // default
        },
        maxRedirects: 5 // default
    }

    var API = {
        get: function(param) {
            // jQuery.support.cors = true;
            //设置基础URL接口地址
            param['api'] = param.api;
            // param['api'] = SITE_INTERFACE_URL + param.api;
            config.data = param.param || {};
            config.responseType = param.dataType || config.responseType;
            var result = axios.get(param.api, {}, config);
            return result.then(function(res) {
                return res.data;
            });
        },
        post: function(param) {
            // jQuery.support.cors = true;
            //设置基础URL接口地址
            param['api'] = param.api;
            // param['api'] = SITE_INTERFACE_URL + param.api;
            config.data = param.param || {};
            config.responseType = param.dataType || config.responseType;
            var result = axios.post(param.api, {}, config);
            return result.then(function(res) {
                return res.data;
            });
        }
    }
    window.API = API;
})();

function getQueryStringByName(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    var context = "";
    if (r != null)
        context = r[2];
    reg = null;
    r = null;
    return context == null || context == "" || context == "undefined" ? "" : context;
}

/*获取地址栏变量的值，会区分变量名的大小写*/
function GetUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}
/*获取地址栏变更的值，会进行URI解码，用于获取中文参数值*/
    function GetDecodeUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = decodeURIComponent(window.location.search).substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}


$(function() {
    FastClick.attach(document.body);
});