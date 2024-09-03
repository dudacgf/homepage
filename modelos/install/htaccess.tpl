{if $error404}
ErrorDocument 404 /homepage/{$error404}
{/if}
{if $error401}
ErrorDocument 401 /homepage/{$error401}
{/if}
{foreach $restricted_folders as $rf name=rr}
RedirectMatch 404 {$rf}
{if $smarty.foreach.rr.last}
#
{/if}
{/foreach}
{if $auth_needed}
{if $allowed_files}<FilesMatch "^(?!({foreach $allowed_files as $af name=aa}{$af}{if !$smarty.foreach.aa.last}|{/if}{/foreach}))">
{/if}
authtype basic
authuserfile /var/www/html/homepage/admin/.htpasswd.ghtpasswd
authname "Secure Area"
require user {$auth_user}
{if $allowed_files}</FilesMatch>
{/if}
{/if}
