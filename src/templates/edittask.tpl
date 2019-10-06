{include file="header.tpl"}

<form action="/?action=updateTask&task={$oTask->getTaskId()}" method="post">

        <div class="form-group">
            <label for="UserName">User name:</label>
            <input type="text" class="form-control" name="user_name" id="UserName" value="{$oTask->getUserName()}" readonly>
        </div>
        <div class="form-group">
            <label for="Email">Email:</label>
            <input type="email" class="form-control" name="email" id="Email" value="{$oTask->getEmail()}" readonly>
        </div>

        <div class="form-group">
            <label for="Text">Description:</label>
            <textarea type="text" class="form-control" rows="10" name="task_text" id="Text" required>{$oTask->getTaskText()}</textarea>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="Check1" name="task_done" value="1" {if $oTask->getTaskDoneMark()}checked{/if}>
            <label class="form-check-label" for="Check1">Task done</label>
        </div>

        <input class="btn btn-success" type="submit" value="Submit">
        <a class="btn btn-danger" href="/?action=main" target="_self">Cancel</a>

</form>

{include file="footer.tpl"}
