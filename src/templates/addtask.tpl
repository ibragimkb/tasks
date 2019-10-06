{include file="header.tpl"}

<form action="/?action=saveTask" method="post">
    <div class="form-group">
        <label for="user_name">User name:</label>
        <input class="form-control" type="text" name="user_name" value="" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input class="form-control" type="email" name="email" value="" required>

    </div>
    <div class="form-group">
        <label for="task_text">Description:</label>
        <textarea class="form-control" type="text" rows="10" name="task_text" required></textarea>
    </div>
    <input class="btn btn-success" type="submit" value="Submit">
    <a class="btn btn-danger" href="/?action=main" target="_self">Cancel</a>
</form>


{include file="footer.tpl"}
