<div class="wrap">
    <?php $this->plugin_settings_tabs(); ?>
    <br>
    <div style="width: 300px;height: 64px;"><?php echo '<img src="' . plugins_url('js/agile500.png', dirname(__FILE__)) . '" > '; ?></div>
    <h3>Do not have an account with Agile CRM? <span style="font-size: 75%;">It's fast and free for Ten users</span></h3>    
    <div style="width: auto; height: auto; color: #8a6d3b; background-color: #fcf8e3; border: 1px solid #faebcc; border-radius: 5px">
        <div style="margin-top: 20px; margin-left: 50px;"><a href="https://www.agilecrm.com/pricing?utm_source=gravityforms&utm_medium=website&utm_campaign=integration" target="_blank" class="button">Create a new account</a></div>
        <p style="margin: 15px 20px 20px 50px;" id="create_account_text">Once you have created, please come back and fill in the details below</p>
    </div>

    <h3>Already have an account? <span style="font-size: 75%;">Enter your details</span></h3>

    <form method="post" action="options.php" id="settings_form"> 
        <?php settings_fields($this->tag . '-settings-group'); ?>
        <?php do_settings_sections($this->tag); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="agilecrm_gf_domain">Domain</label></th>
                <td>
                    <span style="padding:3px; margin:0px; border: 1px solid #dfdfdf; border-right: 0px; background-color:#eee;">https://</span>
                    <input type="text"  name="agilecrm_gf_domain" id="agilecrm_gf_domain" value="<?php echo get_option('agilecrm_gf_domain'); ?>" style="width: 100px; margin: 0px; border-radius: 0px;" oninvalid="_agile_set_custom_validate(this);" oninput="_agile_reset_custom_validate(this);" autocapitalize="off" minlength="4" maxlength="20" pattern="^[a-zA-Z][a-zA-Z0-9-_]{3,20}$" required="">
                    <span style="margin:0px; padding: 3px; border: 1px solid #dfdfdf; background-color:#eee; border-left: 0px;">.agilecrm.com</span><br><small>If you are using abc.agilecrm.com, enter abc</small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="agilecrm_gf_admin_email">User ID (Email Address)</label></th>
                <td>
                    <input type="email" style="width:250px;" name="agilecrm_gf_admin_email" id="agilecrm_gf_admin_email" value="<?php echo get_option('agilecrm_gf_admin_email'); ?>" placeholder="admin user email" maxlength="50"
            minlength="6" value=""  oninvalid="_agile_set_custom_validate(this);" oninput="_agile_reset_custom_validate(this);" required><br>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="agilecrm_gf_api_key">API Key</label></th>
                <td>
                    <input type="text" style="width:250px;" name="agilecrm_gf_api_key" id="agilecrm_gf_api_key" value="<?php echo get_option('agilecrm_gf_api_key'); ?>" placeholder="REST api key" required=""><br>
                    <small>For instructions to find your API key, please click <a href="https://github.com/agilecrm/rest-api#api-key" target="_blank">here</a></small>
                </td>
            </tr>
        </table>
        <?php submit_button('Save Changes', 'primary', 'submit_button', false); ?>&nbsp;<span
                    style="vertical-align: sub;" id="error_text"></span>
    </form>

</div>
<?php
$agilecrm_gf_api_key = get_option('agilecrm_gf_api_key');
$agilecrm_gf_admin_email = get_option('agilecrm_gf_admin_email');
$agilecrm_gf_domain = get_option('agilecrm_gf_domain');
$result = agilegravity_curl_wrap("api-key", null, "GET", "application/json",$agilecrm_gf_admin_email,$agilecrm_gf_api_key,$agilecrm_gf_domain);
if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
   $result = json_decode($result, false, 512, JSON_BIGINT_AS_STRING);
} else {
     $result = json_decode($result, false);
}
if($result->status != '401'){
$js_api_key = $result->js_api_key;
}
if(empty($js_api_key)){ ?>
    <script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js'></script>
      <script type="text/javascript">
    $(document).ready(function() {
         document.getElementById("setting-error-settings_updated").style.display = "none";
         showError('agilecrm_gf_api_key', 'Please verify the above details given in the above fields.', 'agilecrm_gf_domain','agilecrm_gf_admin_email');
    });
    </script> 
<?php }

function agilegravity_curl_wrap($entity, $data, $method, $content_type,$agilecrm_gf_admin_email,$agilecrm_gf_api_key,$agilecrm_gf_domain) {

    if ($content_type == NULL) {
        $content_type = "application/json";
    }
     $agile_url = "https://" . $agilecrm_gf_domain . ".agilecrm.com/dev/api/" . $entity;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
        switch ($method) {
            case "POST":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "GET":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "PUT":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                break;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type : $content_type;", 'Accept : application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $agilecrm_gf_admin_email . ':' . $agilecrm_gf_api_key);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
     }
?>
<style>
#error_text{
    color: rgb(221, 0, 0);
    width: 500px;
    display: inline-block;
    border-radius: 5px;
    padding: 10px;
    margin-top: -22px;
}
</style>
<script>
document.getElementById('submit_button').style.marginBottom = "20px";
document.getElementById('settings_form').style.marginRight = "20px";

function loadxml(){
    alert('hai');
}
function getInputElement(name)
{
    return document.getElementsByName(name)[0];
}

function getErrorElement()
{
    return document.getElementById('error_text');
}

function showError(name, msg, title)
{
    getErrorElement().style.color = "#dd0000";
    getInputElement(name).style.borderColor = "#dd0000";
    if (title)
        getInputElement(title).style.borderColor = "#dd0000";

    if (getErrorElement().innerHTML)
        getErrorElement().innerHTML = msg + ", " + getErrorElement().innerHTML;
    else
        getErrorElement().innerHTML = msg;
}
function successMessage(msg)
{
    getErrorElement().style.color = "#005500";
    getErrorElement().innerHTML = msg;
    setTimeout(function()
    {
        getErrorElement().innerHTML = '';
    }, 5000);
}

function hideError(name)
{
    getInputElement(name).style.borderColor = "#dfdfdf";
    getErrorElement().innerHTML = '';
}
function isFilled(name)
{
    var value = getInputElement(name).value;
    return !(!value || value.length == 0 || value.indexOf(' ') != -1);
}
document.getElementById('submit_button').onclick = function(e)
{
    e.preventDefault();
    document.getElementById('submit_button').value = 'Saving Changes...';
    hideError('agilecrm_gf_domain');
    hideError('agilecrm_gf_api_key');
    hideError('agilecrm_gf_admin_email');
    var domain = getInputElement('agilecrm_gf_domain').value;
    var key = getInputElement('agilecrm_gf_api_key').value;
    var emailid = getInputElement('agilecrm_gf_admin_email').value;

    if (isFilled('agilecrm_gf_domain') && isFilled('agilecrm_gf_api_key') && isFilled('agilecrm_gf_admin_email'))
    {
        var domainresult = /^[\w]+$/.test(domain);
        var validaddress = /^[0-9][A-Za-z0-9]+$/.test(domain);
        var x = getInputElement('agilecrm_gf_admin_email').value;
        var atpos = x.indexOf("@");
        var dotpos = x.lastIndexOf(".");
        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
            showError('agilecrm_gf_admin_email', 'Enter a Valid User ID');
            return false;
        }
        if(domainresult == true && validaddress == false)
        {
        if (getInputElement('agilecrm_gf_domain').value.length < 1)
        {
            showError('agilecrm_gf_domain', 'Enter a Valid Domain Name');
            document.getElementById('submit_button').value = 'Save Changes';
            return false;
        }
        jQuery.ajax({ url : 'https://' + domain + '.agilecrm.com/core/js/api/email?id=' + key + '&email='+emailid, type : 'GET', dataType : 'jsonp',
            success : function(json)
            {
                if (json.hasOwnProperty('error'))
                {
                    showError('agilecrm_gf_api_key', 'Invalid Domain Name or Rest API Key', 'agilecrm_gf_domain');
                    document.getElementById('submit_button').value = 'Save Changes';
                    return false;
                   
                }
                document.getElementById('settings_form').submit();
                document.getElementById('submit_button').value = 'Save Changes';
                //successMessage('Settings saved successfully');
                return;
            } });
      }else{
        showError('agilecrm_gf_domain', 'Name should be between 4-20 characters in length. Both letters and numbers are allowed but it should start with a letter. Cannot contain special characters other than &#39;_&#39; and &#39;-&#39;.');
      }
    }
    else
    {

        if (!isFilled('agilecrm_gf_domain'))
            showError('agilecrm_gf_domain', 'Enter a Valid Domain Name');
        if (!isFilled('agilecrm_gf_api_key'))
            showError('agilecrm_gf_api_key', 'Enter a Valid API Key');
        if (!isFilled('agilecrm_gf_admin_email'))
            showError('agilecrm_gf_admin_email', 'Enter a Valid User ID');
        document.getElementById('submit_button').value = 'Save Changes';
        return false;
    }
};
</script>