<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
add_shortcode( 'frontend', 'torque_hello_world_shortcode' );
function torque_hello_world_shortcode( $atts ) {
             wp_register_script( 'wpsp-registration', WPSPaddon_PLUGIN_URL . 'js/wpsp-registration.js', array('jquery'),'',true  );
             wp_enqueue_script( 'wpsp-registration' );
             wp_register_style('wpsp_addon_front_css', WPSPaddon_PLUGIN_URL . 'css/wpspaddon-style.css', false, '1.0.0');
             wp_enqueue_style('wpsp_addon_front_css');
            
        global $current_user, $wp_roles, $wpdb;
            $temp_table  =   $wpdb->prefix."wpsp_temp";
          if ( isset( $_POST['gg'] ) ) {
            $username        = sanitize_user($_POST['Username']);
            $firstname       = sanitize_text_field($_POST['s_fname']);
            $email           = sanitize_text_field($_POST['Email']);
            $password        = sanitize_text_field($_POST['Password']);
            $selctusertype   = sanitize_text_field($_POST['selctusertype']);
        $studenttable1 = array(
        't_name' => $firstname,
        't_username' => $username,
        't_email' => $email,
        't_password' => $password,
        't_type' => $selctusertype
        );
        $format = array('%s', '%s', '%s', '%s', '%s');
        $userdetails = $wpdb->get_row("select t_email,t_username from $temp_table where t_email = '".$email."' and t_username = '".$username."'", ARRAY_A);
        if(username_exists($username))
        {
         echo '<div class="wpsp-popupMain wpsp-popVisible" id="WarningModal" style="display:block;">
                  <div class="wpsp-overlayer"></div> 
                  <div class="wpsp-popBody wpsp-alert-body"> 
                    <div class="wpsp-popInner">
                        <a href="javascript:;" class="wpsp-closePopup"></a>
                        <div class="wpsp-popup-cont wpsp-alertbox wpsp-alert-warning">
                            <div class="wpsp-alert-icon-box"> 
                                <i class="icon dashicons dashicons-editor-help"></i>
                            </div>
                            <div class="wpsp-alert-data">
                                <h4>Warning</h4>
                                <p class="wpsp-popup-return-data">Email ID Or User Name Already Exists!</p>
                            </div>
                            <div class="wpsp-alert-btn">
                                <button type="submit" class="wpsp-btn wpsp-dark-btn wpsp-popup-cancel">Cancel</button>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>';
        }
       else if (email_exists($email) || $userdetails['t_email'] == $email ){
                echo '<div class="wpsp-popupMain wpsp-popVisible" id="WarningModal" style="display:block;">
                  <div class="wpsp-overlayer"></div> 
                  <div class="wpsp-popBody wpsp-alert-body"> 
                    <div class="wpsp-popInner">
                        <a href="javascript:;" class="wpsp-closePopup"></a>
                        <div class="wpsp-popup-cont wpsp-alertbox wpsp-alert-warning">
                            <div class="wpsp-alert-icon-box"> 
                                <i class="icon dashicons dashicons-editor-help"></i>
                            </div>
                            <div class="wpsp-alert-data">
                                <h4>Warning</h4>
                                <p class="wpsp-popup-return-data">Email ID Or User Name Already Exists!</p>
                            </div>
                            <div class="wpsp-alert-btn">
                                <button type="submit" class="wpsp-btn wpsp-dark-btn wpsp-popup-cancel">Cancel</button>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>';
            } 
            else {
            $success=$wpdb->insert($temp_table, $studenttable1, $format );
            $to= "ami@wpschoolpress.com";
            $subject = "New Registration Request";
            $msg = 'Hi School';
            $msg.= '<br /><br />It is to notify you that you there is a new registration request for the role of ' . $selctusertype;
            $msg.= '<br /><br />User details :<br />';
            $msg.= '<ul><li>Name: '. $firstname .'</li>';  
            $msg.= '<li>Username: '. $username .'</li>';  
            $msg.= '<li>Password: '. $password .'</li>';  
            $msg.= '<li>Email Address: '.  $email  .'</li>';   
             $msg.= '<li>Role: '. $selctusertype .'</li></ul>';        
            $msg.= '<br/><br/>Please <a href="' . site_url() . '/sch-dashboard">Click Here </a>to login to perform action (Approve/Unapprove).<br /><br />';
            $msg.= 'Regards,<br />' . get_bloginfo('name');
            wpspaddon_send_mail($to,$subject,$msg);
           if($success){
                    echo '<div class="wpsp-popupMain wpsp-popVisible" id="SuccessModal" style="display:block;">
                        <div class="wpsp-overlayer"></div> 
                        <div class="wpsp-popBody wpsp-alert-body"> 
                            <div class="wpsp-popInner">
                                <a href="javascript:;" class="wpsp-closePopup"></a>
                                <div class="wpsp-popup-cont wpsp-alertbox wpsp-alert-success">
                                    <div class="wpsp-alert-icon-box"> 
                                        <i class="icon dashicons dashicons-yes"></i>
                                    </div>
                                    <div class="wpsp-alert-data">
                                        <h4>Success</h4>
                                        <p>Thanks for registering. You will receive a confirmation email from the school mentioning the status(approved/unapproved).</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
            }
        }
    $content =  '<section class="wpsp-container wpsp-form-body addon-front-end-form ">
   <div class="wpsp-row">
      <form method = "post" id="registerrequest" name="registerrequest">
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="firstname">Firstname<span class="wpsp-required">*</span></label>
               <input type="text" class="wpsp-form-control" id="firstname" name="s_fname" placeholder="First Name" required>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="middlename">Middle Name<span class="wpsp-required">*</span></label>
               <input type="text" class="wpsp-form-control" id="middlename" name="s_mname" placeholder="Middle Name" required>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="lastname">Last Name <span class="wpsp-required">*</span></label>
               <input type="text" class="wpsp-form-control chk-lastname" id="lastname" name="s_lname" placeholder="Last Name" required>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
              <label class="wpsp-label" for="dateofbirth">Date of Birth<span class="wpsp-required"></span></label>
              <input type="text" class="wpsp-form-control select_date valid" data-is_required="" id="Dob" name="s_dob" placeholder="mm/dd/yyyy"  aria-invalid="false">
            </div>            
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="gender">Gender</label>
                <div class="wpsp-radio-inline">
                    <div class="wpsp-radio">
                        <input type="radio" name="s_gender" value="Male" checked="checked" id="Male">
                        <label for="Male">Male</label>
                    </div>
                    <div class="wpsp-radio">
                        <input type="radio" name="s_gender" value="Female" id="Female">
                        <label for="Female">Female</label>
                    </div>
                    <div class="wpsp-radio">
                        <input type="radio" name="s_gender" value="other" id="other">
                        <label for="other">Other</label>
                    </div>
                </div>
            </div>        
         </div>
        <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="Address">Current Address
                <span class="wpsp-required">*</span></label>
                <input type="text" name="s_address" data-is_required="1" class="wpsp-form-control" rows="4" id="current_address">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label for="Address">Permanent Address
                <span class="wpsp-required"></span></label>
                 <input type="text" class="wpsp-form-control" data-is_required="" rows="5" id="permanent_address" name="s_paddress">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="Country">Country                                  <span class="wpsp-required"></span></label>
            <select class="wpsp-form-control valid" data-is_required="" id="permanent_country" name="s_country" aria-invalid="false">
            <option value="">Select Country</option>
            <option value="Afghanistan">Afghanistan</option>
            <option value="Åland Islands">Åland Islands</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Anguilla">Anguilla</option>
            <option value="Antarctica">Antarctica</option>
            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
            <option value="Argentina">Argentina</option>
            <option value="Armenia">Armenia</option>
            <option value="Aruba">Aruba</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Bahamas">Bahamas</option>
            <option value="Bahrain">Bahrain</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Barbados">Barbados</option>
            <option value="Belarus">Belarus</option>
            <option value="Belgium">Belgium</option>
            <option value="Belau">Belau</option>
            <option value="Belize">Belize</option>
            <option value="Benin">Benin</option>
            <option value="Bermuda">Bermuda</option>
            <option value="Bhutan">Bhutan</option>
            <option value="Bolivia">Bolivia</option>
            <option value="Bonaire, Saint Eustatius and Saba">Bonaire, Saint Eustatius and Saba</option>
            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
            <option value="Botswana">Botswana</option>
            <option value="Bouvet Island">Bouvet Island</option>
            <option value="Brazil">Brazil</option>
            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
            <option value="British Virgin Islands">British Virgin Islands</option>
            <option value="Brunei">Brunei</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burundi">Burundi</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Canada">Canada</option>
            <option value="Cape Verde">Cape Verde</option>
            <option value="Cayman Islands">Cayman Islands</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Christmas Island">Christmas Island</option>
            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Congo (Brazzaville)">Congo (Brazzaville)</option>
            <option value="Congo (Kinshasa)">Congo (Kinshasa)</option>
            <option value="Cook Islands">Cook Islands</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="Croatia">Croatia</option>
            <option value="Cuba">Cuba</option>
            <option value="CuraÇao">CuraÇao</option>
            <option value="Cyprus">Cyprus</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Denmark">Denmark</option>
            <option value="Djibouti">Djibouti</option>
            <option value="Dominica">Dominica</option>
            <option value="Dominican Republic">Dominican Republic</option>
            <option value="Ecuador">Ecuador</option>
            <option value="Egypt">Egypt</option>
            <option value="El Salvador">El Salvador</option>
            <option value="Equatorial Guinea">Equatorial Guinea</option>
            <option value="Eritrea">Eritrea</option>
            <option value="Estonia">Estonia</option>
            <option value="Ethiopia">Ethiopia</option>
            <option value="Falkland Islands">Falkland Islands</option>
            <option value="Faroe Islands">Faroe Islands</option>
            <option value="Fiji">Fiji</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="French Guiana">French Guiana</option>
            <option value="French Polynesia">French Polynesia</option>
            <option value="French Southern Territories">French Southern Territories</option>
            <option value="Gabon">Gabon</option>
            <option value="Gambia">Gambia</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Gibraltar">Gibraltar</option>
            <option value="Greece">Greece</option>
            <option value="Greenland">Greenland</option>
            <option value="Grenada">Grenada</option>
            <option value="Guadeloupe">Guadeloupe</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guernsey">Guernsey</option>
            <option value="Guinea">Guinea</option>
            <option value="Guinea-Bissau">Guinea-Bissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
            <option value="Honduras">Honduras</option>
            <option value="Hong Kong">Hong Kong</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran">Iran</option>
            <option value="Iraq">Iraq</option>
            <option value="Republic of Ireland">Republic of Ireland</option>
            <option value="Isle of Man">Isle of Man</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Ivory Coast">Ivory Coast</option>
            <option value="Jamaica">Jamaica</option>
            <option value="Japan">Japan</option>
            <option value="Jersey">Jersey</option>
            <option value="Jordan">Jordan</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Kenya">Kenya</option>
            <option value="Kiribati">Kiribati</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Laos">Laos</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libya">Libya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Macao S.A.R., China">Macao S.A.R., China</option>
            <option value="Macedonia">Macedonia</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Martinique">Martinique</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mayotte">Mayotte</option>
            <option value="Mexico">Mexico</option>
            <option value="Micronesia">Micronesia</option>
            <option value="Moldova">Moldova</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montenegro">Montenegro</option>
            <option value="Montserrat">Montserrat</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="Netherlands Antilles">Netherlands Antilles</option>
            <option value="New Caledonia">New Caledonia</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="Niue">Niue</option>
            <option value="Norfolk Island">Norfolk Island</option>
            <option value="North Korea">North Korea</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palestinian Territory">Palestinian Territory</option>
            <option value="Panama">Panama</option>
            <option value="Papua New Guinea">Papua New Guinea</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Pitcairn">Pitcairn</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Qatar">Qatar</option>
            <option value="Reunion">Reunion</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russia</option>
            <option value="Rwanda">Rwanda</option>
            <option value="Saint Barthélemy">Saint Barthélemy</option>
            <option value="Saint Helena">Saint Helena</option>
            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
            <option value="Saint Lucia">Saint Lucia</option>
            <option value="Saint Martin (French part)">Saint Martin (French part)</option>
            <option value="Saint Martin (Dutch part)">Saint Martin (Dutch part)</option>
            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
            <option value="San Marino">San Marino</option>
            <option value="São Tomé and Príncipe">São Tomé and Príncipe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Senegal">Senegal</option>
            <option value="Serbia">Serbia</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra Leone">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Georgia/Sandwich Islands">South Georgia/Sandwich Islands</option>
            <option value="South Korea">South Korea</option>
            <option value="South Sudan">South Sudan</option>
            <option value="Spain">Spain</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
            <option value="Swaziland">Swaziland</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syria">Syria</option>
            <option value="Taiwan">Taiwan</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania">Tanzania</option>
            <option value="Thailand">Thailand</option>
            <option value="Timor-Leste">Timor-Leste</option>
            <option value="Togo">Togo</option>
            <option value="Tokelau">Tokelau</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom (UK)">United Kingdom (UK)</option>
            <option value="United States (US)">United States (US)</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Vatican">Vatican</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Vietnam">Vietnam</option>
            <option value="Wallis and Futuna">Wallis and Futuna</option>
            <option value="Western Sahara">Western Sahara</option>
            <option value="Western Samoa">Western Samoa</option>
            <option value="Yemen">Yemen</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option>
            </select>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
            <label class="wpsp-label" for="Zipcode">Zip Code
            <span class="wpsp-required"></span></label>
                <input type="text" class="wpsp-form-control" id="current_pincode" name="s_zipcode" data-is_required="">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="s_phone">Phone Number
                <span class="wpsp-required"></span></label>
                <input type="text" data-is_required="" class="wpsp-form-control" id="s_phone" name="s_phone">
            </div>
         </div>
        <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
          <label class="wpsp-label" for="bloodgroup">Blood Group                                  <span class="wpsp-required"></span></label>
            <select class="wpsp-form-control valid" data-is_required="" id="Bloodgroup" name="s_bloodgrp" aria-invalid="false">
                <option value="">Select Blood Group</option>
                <option value="O+">O +</option>
                <option value="O-">O -</option>
                <option value="A+">A +</option>
                <option value="A-">A -</option>
                <option value="B+">B +</option>
                <option value="B-">B -</option>
                <option value="AB+">AB +</option>
                <option value="AB-">AB -</option>
            </select>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="Doj">Joining Date 
                <span class="wpsp-required"></span></label>
                <input type="text" class="wpsp-form-control select_date Doj valid" id="Doj" name="s_doj" value="" placeholder="mm/dd/yyyy" aria-invalid="false">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="class">Class 
                        <span class="wpsp-required"></span>
                </label>
                <select class="wpsp-form-control" data-is_required="" name="class_id">
                <option value="" disabled="" selected="">Select Class</option>
                  </select>
            </div>
         </div>
          <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
            <label class="wpsp-label" for="dateofbirth">Roll Number
                <span class="wpsp-required">*</span> </label>
            <input type="text" data-is_required="1" class="wpsp-form-control" id="Rollno" name="s_rollno">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="Doj">Class Date 
                <span class="wpsp-required"></span></label>
                <input type="text" class="wpsp-form-control select_date Doj valid" id="Doj" name="class_date" value="" placeholder="mm/dd/yyyy" aria-invalid="false">
            </div>
         </div>
        <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
           <div class="wpsp-form-group">
            <label class="wpsp-label" for="Zipcode">Zip Code
            <span class="wpsp-required"></span></label>
                <input type="text" class="wpsp-form-control" id="current_pincode" name="s_pzipcode" data-is_required="">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="Country">Country                                  <span class="wpsp-required"></span></label>
            <select class="wpsp-form-control valid" data-is_required="" id="permanent_country" name="s_pcountry" aria-invalid="false">
            <option value="">Select Country</option>
            <option value="Afghanistan">Afghanistan</option>
            <option value="Åland Islands">Åland Islands</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Anguilla">Anguilla</option>
            <option value="Antarctica">Antarctica</option>
            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
            <option value="Argentina">Argentina</option>
            <option value="Armenia">Armenia</option>
            <option value="Aruba">Aruba</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Bahamas">Bahamas</option>
            <option value="Bahrain">Bahrain</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Barbados">Barbados</option>
            <option value="Belarus">Belarus</option>
            <option value="Belgium">Belgium</option>
            <option value="Belau">Belau</option>
            <option value="Belize">Belize</option>
            <option value="Benin">Benin</option>
            <option value="Bermuda">Bermuda</option>
            <option value="Bhutan">Bhutan</option>
            <option value="Bolivia">Bolivia</option>
            <option value="Bonaire, Saint Eustatius and Saba">Bonaire, Saint Eustatius and Saba</option>
            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
            <option value="Botswana">Botswana</option>
            <option value="Bouvet Island">Bouvet Island</option>
            <option value="Brazil">Brazil</option>
            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
            <option value="British Virgin Islands">British Virgin Islands</option>
            <option value="Brunei">Brunei</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burundi">Burundi</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Canada">Canada</option>
            <option value="Cape Verde">Cape Verde</option>
            <option value="Cayman Islands">Cayman Islands</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Christmas Island">Christmas Island</option>
            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Congo (Brazzaville)">Congo (Brazzaville)</option>
            <option value="Congo (Kinshasa)">Congo (Kinshasa)</option>
            <option value="Cook Islands">Cook Islands</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="Croatia">Croatia</option>
            <option value="Cuba">Cuba</option>
            <option value="CuraÇao">CuraÇao</option>
            <option value="Cyprus">Cyprus</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Denmark">Denmark</option>
            <option value="Djibouti">Djibouti</option>
            <option value="Dominica">Dominica</option>
            <option value="Dominican Republic">Dominican Republic</option>
            <option value="Ecuador">Ecuador</option>
            <option value="Egypt">Egypt</option>
            <option value="El Salvador">El Salvador</option>
            <option value="Equatorial Guinea">Equatorial Guinea</option>
            <option value="Eritrea">Eritrea</option>
            <option value="Estonia">Estonia</option>
            <option value="Ethiopia">Ethiopia</option>
            <option value="Falkland Islands">Falkland Islands</option>
            <option value="Faroe Islands">Faroe Islands</option>
            <option value="Fiji">Fiji</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="French Guiana">French Guiana</option>
            <option value="French Polynesia">French Polynesia</option>
            <option value="French Southern Territories">French Southern Territories</option>
            <option value="Gabon">Gabon</option>
            <option value="Gambia">Gambia</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Gibraltar">Gibraltar</option>
            <option value="Greece">Greece</option>
            <option value="Greenland">Greenland</option>
            <option value="Grenada">Grenada</option>
            <option value="Guadeloupe">Guadeloupe</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guernsey">Guernsey</option>
            <option value="Guinea">Guinea</option>
            <option value="Guinea-Bissau">Guinea-Bissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
            <option value="Honduras">Honduras</option>
            <option value="Hong Kong">Hong Kong</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran">Iran</option>
            <option value="Iraq">Iraq</option>
            <option value="Republic of Ireland">Republic of Ireland</option>
            <option value="Isle of Man">Isle of Man</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Ivory Coast">Ivory Coast</option>
            <option value="Jamaica">Jamaica</option>
            <option value="Japan">Japan</option>
            <option value="Jersey">Jersey</option>
            <option value="Jordan">Jordan</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Kenya">Kenya</option>
            <option value="Kiribati">Kiribati</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Laos">Laos</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libya">Libya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Macao S.A.R., China">Macao S.A.R., China</option>
            <option value="Macedonia">Macedonia</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Martinique">Martinique</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mayotte">Mayotte</option>
            <option value="Mexico">Mexico</option>
            <option value="Micronesia">Micronesia</option>
            <option value="Moldova">Moldova</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montenegro">Montenegro</option>
            <option value="Montserrat">Montserrat</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="Netherlands Antilles">Netherlands Antilles</option>
            <option value="New Caledonia">New Caledonia</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="Niue">Niue</option>
            <option value="Norfolk Island">Norfolk Island</option>
            <option value="North Korea">North Korea</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palestinian Territory">Palestinian Territory</option>
            <option value="Panama">Panama</option>
            <option value="Papua New Guinea">Papua New Guinea</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Pitcairn">Pitcairn</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Qatar">Qatar</option>
            <option value="Reunion">Reunion</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russia</option>
            <option value="Rwanda">Rwanda</option>
            <option value="Saint Barthélemy">Saint Barthélemy</option>
            <option value="Saint Helena">Saint Helena</option>
            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
            <option value="Saint Lucia">Saint Lucia</option>
            <option value="Saint Martin (French part)">Saint Martin (French part)</option>
            <option value="Saint Martin (Dutch part)">Saint Martin (Dutch part)</option>
            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
            <option value="San Marino">San Marino</option>
            <option value="São Tomé and Príncipe">São Tomé and Príncipe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Senegal">Senegal</option>
            <option value="Serbia">Serbia</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra Leone">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Georgia/Sandwich Islands">South Georgia/Sandwich Islands</option>
            <option value="South Korea">South Korea</option>
            <option value="South Sudan">South Sudan</option>
            <option value="Spain">Spain</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
            <option value="Swaziland">Swaziland</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syria">Syria</option>
            <option value="Taiwan">Taiwan</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania">Tanzania</option>
            <option value="Thailand">Thailand</option>
            <option value="Timor-Leste">Timor-Leste</option>
            <option value="Togo">Togo</option>
            <option value="Tokelau">Tokelau</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom (UK)">United Kingdom (UK)</option>
            <option value="United States (US)">United States (US)</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Vatican">Vatican</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Vietnam">Vietnam</option>
            <option value="Wallis and Futuna">Wallis and Futuna</option>
            <option value="Western Sahara">Western Sahara</option>
            <option value="Western Samoa">Western Samoa</option>
            <option value="Yemen">Yemen</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option>
            </select>
            </div>
         </div>
        <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">City Name
            <span class="wpsp-required"></span>  </label>
            <input type="text" class="wpsp-form-control" data-is_required="" id="current_city" name="s_city">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">City Name
            <span class="wpsp-required"></span>  </label>
            <input type="text" class="wpsp-form-control" data-is_required="" id="current_city" name="s_pcity">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="firstname">Firstname<span class="wpsp-required">*</span></label>
               <input type="text" class="wpsp-form-control" id="firstname" name="p_fname" placeholder="First Name" required>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="middlename">Middle Name<span class="wpsp-required">*</span></label>
               <input type="text" class="wpsp-form-control" id="middlename" name="p_mname" placeholder="Middle Name" required>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="lastname">Last Name <span class="wpsp-required">*</span></label>
               <input type="text" class="wpsp-form-control chk-lastname" id="lastname" name="p_lname" placeholder="Last Name" required>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="p_gender">Gender</label>
                <div class="wpsp-radio-inline">
                    <div class="wpsp-radio">
                        <input type="radio" name="p_gender" value="Male" checked="checked" id="p_Male">
                        <label for="Male">Male</label>
                    </div>
                    <div class="wpsp-radio">
                        <input type="radio" name="p_gender" value="Female" id="p_Female">
                        <label for="Female">Female</label>
                    </div>
                    <div class="wpsp-radio">
                        <input type="radio" name="p_gender" value="other" id="p_other">
                        <label for="other">Other</label>
                    </div>
                </div>
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="p_edu">Education                                <span class="wpsp-required"></span></label>
                <input type="text" data-is_required="" class="wpsp-form-control" name="p_edu" id="p_edu">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="phone">Phone                                    <span class="wpsp-required"></span> </label>
                <input type="text" data-is_required="" class="wpsp-form-control" id="p_phone" name="p_phone">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="p_profession"> Profession<span class="wpsp-required"></span></label>
                <input type="text" data-is_required="" class="wpsp-form-control" name="p_profession" id="p_profession">
            </div>
         </div>
          <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
                <label class="wpsp-label" for="pbloodgroup">Blood Group    
                <span class="wpsp-required"></span> </label>
                <select class="wpsp-form-control valid" data-is_required="" id="p_bloodgroup" name="p_bloodgroup" aria-invalid="false">
                    <option value="">Select Blood Group</option>
                    <option value="O+">O option>
                    <option value="O-">O -</+</option>
                    <option value="A+">A +</option>
                    <option value="A-">A -</option>
                    <option value="B+">B +</option>
                    <option value="B-">B -</option>
                    <option value="AB+">AB +</option>
                    <option value="AB-">AB -</option>
                </select>
            </div>
         </div>







         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="Email">Email Address <span class="wpsp-required">*</span></label>
               <input type="email" class="wpsp-form-control chk-email" id="Email" name="Email" placeholder="Email" required>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="Password">Password <span class="wpsp-required">*</span></label>
               <input type="password" class="wpsp-form-control" id="Password" name="Password" placeholder="Password" required="true" minlength="5">
            </div>
         </div>
         <div class="wpsp-col-lg-6  wpsp-col-md-6  wpsp-col-sm-6  wpsp-col-xs-12">
            <div class="wpsp-form-group">
               <label class="wpsp-label" for="bloodgroup">User Type</label>
               <select class="wpsp-form-control" id="selctusertype" name="selctusertype" required>
                  <option value="">Select User Type</option>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
               </select>
            </div>
         </div>
   </div>
   <div class="wpsp-row">
   <div class="wpsp-col-lg-12 wpsp-col-md-12 wpsp-col-sm-12 wpsp-col-xs-12 wpsp-text-center">
   <input type="submit" name="gg" class="wpsp-btn">
   </div>
   </form>
   </div>
</section>
';
    return  $content;
}