{include file="header.tpl"}

<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="btn btn-warning" href="/?action=addTask" target="_self">Add task</a>
    </li>

</ul>

<table class="table" style="width:100%">
    <thead class="thead-light">
    <tr>

        <th style="width:15%"><a class="btn btn-light" href="/?action=main&order=1" target="_self">Username</a></th>
        <th style="width:15%"><a class="btn btn-light" href="/?action=main&order=2" target="_self">Email</a></th>
        <th style="width:50%"><a class="btn btn-light">Task</a></th>
        <th style="width:10%"><a class="btn btn-light" href="/?action=main&order=3" target="_self">Status</a></th>
        <th style="width:10%">
            {if $oUser->getUserIsLogin()}
                <a class="btn btn-danger" href="/?action=logout" target="_self">Logout</a>
            {else}
                <a class="btn btn-primary" href="/?action=login" target="_self">Login</a>
            {/if}
        </th>
    </tr>
    </thead>

    <tbody>
    {foreach from=$aTasks item=task}
        <tr>
            <td>{$task->getUserName()}</td>
            <td>{$task->getEmail()}</td>
            <td>{$task->getTaskText()}</td>
            <td>
                {if $task->getTaskDoneMark() == TRUE}
                Done </br>
                {else}

                {/if}
                {if $task->getTaskEditedMark() == TRUE}
                edit by admin
                {/if}
            </td>
            <td>
                {if $oUser->getUserIsLogin() && $oUser->getUserIsAdmin()}
                    <a class="btn btn-warning" href="/?action=editTask&task={$task->getTaskId()}" target="_self">Edit task</a>
                {/if}
            </td>
        </tr>
    {/foreach}
    </tbody>

</table>


<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        {foreach from=$aPages item=p}
            <li {if $p == $iPage}class="page-item active"{else}class="page-item"{/if}><a class="page-link" href="/?action=main&page={$p}">{$p}</a></li>
        {/foreach}
    </ul>
</nav>

{include file="footer.tpl"}
