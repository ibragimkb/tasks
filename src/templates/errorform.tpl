{include file="header.tpl"}

<div class="alert alert-warning" role="alert">
    Error form data
</div>

{foreach from=$aError item=error}
    <p>{$error}</p>
{/foreach}

<a class="btn btn-danger" href="/?action=addTask" target="_self">Back</a>

{include file="footer.tpl"}
