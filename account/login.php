<style>
body {
    overflow: hidden;
}

#loginsegment {
    width: 40%;
    margin-left: 30%;
    margin-top: 10%;
    background-color: rgba(255, 255, 255, 0.1);
}
</style>

<div class="ui red segment" id="loginsegment">
    <br />
    <form class="ui inverted form" id="loginForm" method="POST">
        <h1 class="ui inverted center aligned header" style="font-size: 50px;">
            <i class="book icon"></i> Dark Library
        </h1>
        <p>
            <div class="ui left fluid inverted icon input">
                <input placeholder="Username" name="username" type="text" maxlength="32">
                <i class="user icon"></i>
            </div>
        </p>
        <p>
            <div class="ui left fluid inverted icon input">
                <input placeholder="Password" name="password" type="password">
                <i class="Privacy icon"></i>
            </div>
        </p>
        <button type="submit" class="ui submit red inverted basic fluid button">Let me in</div>
    </form>
</div>

<script>
function submit() {
    $(".submit.button").addClass("loading");
    $("#loginForm").off("submit").on("submit", false);

    $.post("<?= $def_cred->rootURL ?>account/createsession.php", $("#loginForm").serializeArray(), function(response) {
        if (response != 0) {
            // Print error alert
            errorAlert(response);
        } else {
            $("#loginsegment").popup("hide");
            location.reload();
        }

	}).fail(function() {
        errorAlert("c0");
	}).always(function() {
        $(".submit.button").removeClass("loading");
        $("#loginForm").on("submit", submit);
    })

    // Prevent form from being submitted
    return false;
}

function errorAlert(id) {
    $("#loginsegment").popup({
        on: "manual",
        position: "bottom center",
        variation: "inverted",
        content: getErrorMessage(id)
    }).popup("show");
}

$("#loginForm").on("submit", submit);
</script>
