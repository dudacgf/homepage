{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
<div class="login">
    <input type="hidden" id="target_url" value="{$target_url}" />
    <h1>Login</h1>
    <form action="authenticate.php" method="post">
    <label for="username">
        <i class="fas fa-user"></i>
    </label>
    <input type="text" name="username" placeholder="Username" id="username" required autocomplete="username">
    <label for="password">
        <i class="fas fa-lock"></i>
    </label>
    <input type="password" name="password" pattern="{literal}(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+.\-]).{8,12}{/literal}" 
        placeholder="Password" id="password" required>
    <input type="submit" value="Login">
    </form>
</div>
{include file="page_footer.tpl"}
