<div class="wrap">
    <h2>Visitor Engage Integration</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('visitor_engage-group'); ?>
        <?php @do_settings_fields('visitor_engage-group'); ?>

        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="domain_id">Account ID</label></th>
                <td><input type="text" name="domain_id" id="domain_id" value="<?php echo get_option('domain_id'); ?>" /></td>
            </tr>
        </table>
        <?php @submit_button(); ?>
    </form>
    <a href="#" id="getDomainIdBtn">Get Domain ID</a>
    <form action="post" onclick="return false;" style="display:none" id="getDomainIdForm">
        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="email">Email</label></th>
                <td><input type="text" name="email" id="va_email" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="password">Password</label></th>
                <td><input type="password" name="password" id="va_password" /></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary" id="submitDomainIdForm">
        </p>
        <div id="va-status"></div>
    </form>

</div>