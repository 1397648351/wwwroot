/**
 * Created by light on 2018/3/28.
 */
var login = function () {
    return {
        init: function (loginUrl, successUrl) {
            //localStorage && localStorage.getItem('key') && $('#key').val(localStorage.getItem('key'));
            //localStorage && localStorage.getItem('password') && $('#password').val(localStorage.getItem('password'));
            localStorage && localStorage.getItem('mobile') && $('#phone').val(localStorage.getItem('mobile'));

            var errMsg = {
                key: '请输入正确的账号',
                password: '请输入您的登录密码',
                mobile: '请输入正确的手机号码'
            };

            var showError = function (msg) {
                alert(msg);
            };

            $("#login").click(function () {
                var type = $(".tab-active").data('for');
                var loginType, key, password, mobile, code, remember;
                if (type == '#normal') {
                    loginType = 1;
                    key = $("#key").val();
                    if (!key || key == "") {
                        alert("邮箱/用户名不能为空！");
                        return;
                    }
                    //localStorage && localStorage.setItem("key", key);
                    password = $("#password").val();
                    if (!password || password == "") {
                        alert("密码不能为空！");
                        return;
                    }
                    //localStorage && localStorage.setItem("password", password);
                    remember = $("#remember")[0].checked
                } else {
                    loginType = 2;
                    mobile = $("#phone").val();
                    if (!mobile || mobile == "") {
                        alert("手机号不能为空！");
                        return;
                    }
                    localStorage && localStorage.setItem("mobile", mobile);
                    code = $("#code").val();
                    if (!mobile || mobile == "") {
                        alert("验证码不能为空！");
                        return;
                    }
                }
                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: loginUrl,
                    data: {
                        loginType: loginType,
                        key: key,
                        password: password,
                        mobile: mobile,
                        verification_code: code,
                        remember: remember
                    },
                    success: function (res) {
                        console.log(res);
                        if (res.status_code == 200) {
                            location.href = successUrl;
                        } else {
                            showError('用户名或密码错误');
                        }
                    },
                    error: function (msg) {
                        console.error(msg);
                        showError('服务器忙，请稍后再试');
                    }
                });
            })
        }
    }
}();

