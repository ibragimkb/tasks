{include file="header.tpl"}

<form action="/?action=auth" method="post">
    <div class="form-group">
        <label for="user_name">Login:</label>
        <input type="text" class="form-control" name="login" value="" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" name="password" value="" required><br>
    </div>

    <input type="submit" class="btn btn-success" value="Submit">
    <a class="btn btn-danger" href="/?action=main" target="_self">back</a>
</form>



{include file="footer.tpl"}
