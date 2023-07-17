<?php




/* This function is used for Add Student */
function wpsp_AddStudent(){
	wpsp_Authenticate();
	if (!isset($_POST['sregister_nonce']))
	{
		echo esc_html("Unauthorized Submission!!", "wpschoolpress");
		exit;
	}
	$username = sanitize_user($_POST['Username']);
	if (wpsp_CheckUsername($username, true) === true)
	{
		echo esc_html("Given Student User Name Already Exists!", "wpschoolpress");
		exit;
	}
	if (email_exists(sanitize_email($_POST['email'])))
	{
		echo esc_html("Student Email ID Already Exists!", "wpschoolpress");
		exit;
	}
	if (strtolower(sanitize_user($_POST['Username'])) == strtolower(sanitize_user($_POST['pUsername'])))
	{
		echo esc_html("Both USer Name Should Not Be same", "wpschoolpress");
		exit;
	}
	if (strtolower(sanitize_email($_POST['pEmail'])) == strtolower(sanitize_email($_POST['Email'])))
	{
		echo  esc_html("Both Email Address Should Not Be same", "wpschoolpress");
		exit;
	}

  if(($_POST['pEmail'] == '') AND (($_POST['pPassword'] != '') OR ($_POST['pConfirmPassword'] != '') OR ($_POST['pUsername'] != ''))){
    echo  esc_html("Please enter parent email", "wpschoolpress");
    exit;
  }

  if(($_POST['pUsername'] == '') AND (($_POST['pEmail'] != '') OR ($_POST['pConfirmPassword'] != '') OR ($_POST['pPassword'] != ''))){
    echo esc_html("Please enter parent username", "wpschoolpress");
    exit;
  }



	global $wpdb;
	$wpsp_student_table = $wpdb->prefix . "wpsp_student";
	$wpsp_class_table = $wpdb->prefix . "wpsp_class";
	$wpsp_class_mapping_table = $wpdb->prefix . "wpsp_class_mapping";


	if (isset($_POST['Class']) && !empty($_POST['Class'])){

		$classID = array_map( 'intval', $_POST['Class'] );
        $classarray = array();

        if(is_numeric($classID) ){
            $classarray[] = $classID;
        }else{
            $class_id_array = $classID;
        foreach ($class_id_array as $id) {
            $classarray[] = $id;
        }
        }

    $messages = '';
    foreach ($classarray as $id) {
      $c = esc_sql($id);
      $capacity = $wpdb->get_var("SELECT c_capacity FROM $wpsp_class_table where cid='$c'");
      $class_array = $wpdb->get_results("SELECT c_name, cid FROM $wpsp_class_table where cid='$c'");
      $classname = $class_array[0]->c_name;
      foreach ($class_array as $value) {
        $class_id_array = $value->cid;
      }

      $classes = $wpdb->get_results("SELECT class_id FROM $wpsp_student_table");
      $classarray = array();
      foreach ($classes as $id) {
        if(is_numeric($id->class_id) ){
          $classarray[] = $id->class_id;
        }else{
          $class_id_array = unserialize($id->class_id);
          foreach ($class_id_array as $cid) {
            $classarray[] = $cid;
           }
        }
      }
      $capacity_array = array_count_values($classarray);

      if (!empty($capacity)){
        if(isset($capacity_array[intval($c)])){
          if (($capacity_array[intval($c)]) >= $capacity){
            $messages .= $classname.", ";
          }
        }
      }

    }
    if ($messages != ''){
      echo '<strong>'.esc_html($messages).'</strong> can not be assigned as it is full. Please remove it and submit.';
      exit;
    }
	}

	global $wpdb;
	$parentMsg = '';
	$parentSendmail = false;
	$wpsp_student_table = $wpdb->prefix . "wpsp_student";
	$firstname = sanitize_text_field($_POST['s_fname']);
	$parent_id = isset($_POST['Parent']) ? sanitize_text_field($_POST['Parent']) : '0';
	$email = sanitize_email($_POST['Email']);
	$pfirstname = sanitize_text_field($_POST['p_fname']);
	$pmiddlename = sanitize_text_field($_POST['p_mname']);
	$plastname = sanitize_text_field($_POST['p_lname']);
	$pgender = sanitize_text_field($_POST['p_gender']);
	$pedu = sanitize_text_field($_POST['p_edu']);
	$pprofession = sanitize_text_field($_POST['p_profession']);
	$pbloodgroup = sanitize_text_field($_POST['p_bloodgrp']);
	$s_p_phone = sanitize_text_field($_POST['s_p_phone']);
	$email = empty($email) ? wpsp_EmailGen($username) : $email;
	$userInfo = array(
		'user_login' => $username,
		'user_pass' => sanitize_text_field($_POST['Password']) ,
		'user_nicename' => sanitize_text_field($_POST['s_fname']) ,
		'first_name' => $firstname,
		'user_email' => $email,
		'role' => 'student'
	);

  $user_id = wp_insert_user($userInfo);

	if (!empty($_POST['pEmail'])){
		$response = getparentInfo(sanitize_email($_POST['pEmail'])); //check for parent email id
		if (isset($response['parentID']) && !empty($response['parentID'])){
			//Use data of existing user
			$parent_id = $response['parentID'];
			$pfirstname = $response['data']->p_fname;
			$pmiddlename = $response['data']->p_mname;
			$plastname = $response['data']->p_lname;
			$pgender = $response['data']->p_gender;
			$pedu = $response['data']->p_edu;
			$pprofession = $response['data']->p_profession;
			$pbloodgroup = $response['data']->p_bloodgrp;
		}
		else{
			if(($_POST['pPassword'] == '') AND (($_POST['pEmail'] != '') OR ($_POST['pConfirmPassword'] != '') OR ($_POST['pUsername'] != ''))){
        echo esc_html("Please enter parent password", "wpschoolpress");
        exit;
      }
      if(($_POST['pConfirmPassword'] == '') AND (($_POST['pEmail'] != '') OR ($_POST['pPassword'] != '') OR ($_POST['pUsername'] != ''))){
        echo esc_html("Please enter parent confirm password", "wpschoolpress");
        exit;
      }

      if(($_POST['pConfirmPassword'] != '') AND ($_POST['pEmail'] != '') AND ($_POST['pPassword'] != '') AND ($_POST['pUsername'] != '')){
        if($_POST['p_fname'] == ''){
          echo esc_html("Please enter parent first name", "wpschoolpress");
          exit;
        }
      }

      if (wpsp_CheckUsername(sanitize_user($_POST['pUsername']) , true) === true)
      {
				$parentMsg = esc_html("Parent UserName Already Exists", "wpschoolpress");
			}
			else
			{
				$parentInfo = array(
					'user_login' => sanitize_user($_POST['pUsername']) ,
					'user_pass' => sanitize_text_field($_POST['pPassword']) ,
					'user_nicename' => sanitize_user($_POST['pUsername']) ,
					'first_name' => sanitize_text_field($_POST['pfirstname']) ,
					'user_email' => sanitize_email($_POST['pEmail']) ,
					'role' => 'parent'
				);

				$parent_id = wp_insert_user($parentInfo); //Creating parent
				$msg = 'Hello ' . sanitize_text_field($_POST['pfirstname']);
				$msg.= '<br />Your are registered as parent at <a href="' . esc_url(site_url()) . '">School</a><br /><br />';
				$msg.= 'Your Login details are below.<br />';
				$msg.= 'Your User Name is : ' . sanitize_user($_POST['pUsername']) . '<br />';
				$msg.= 'Your Password is : ' . sanitize_text_field($_POST['pPassword']) . '<br /><br />';
				$msg.= 'Please Login by clicking <a href="' . esc_url(site_url() . '/sch-dashboard').'">Here </a><br /><br />';
				$msg.= 'Thanks,<br />' . get_bloginfo('name');
				wpsp_send_mail(sanitize_email($_POST['pEmail']) , 'User Registered', $msg);
				if (!is_wp_error($parent_id) && !empty($_FILES['pdisplaypicture']['name']))
				{
					$parentSendmail = true;
					$avatar = uploadImage('pdisplaypicture');
					if (isset($avatar['url']))
					{ //Update parent's profile image
						update_user_meta($parent_id, 'displaypicture', array(
							'full' => $avatar['url']
						));
						update_user_meta($parent_id, 'simple_local_avatar', array(
							'full' => $avatar['url']
						));
					}
				}
				else
				if (is_wp_error($parent_id))
				{
					$parentMsg = $parent_id->get_error_message();
					$parent_id = '';
					$pfirstname = $pmiddlename = $plastname = $pgender = $pedu = $pprofession = $pbloodgroup = '';
				}
			}
		}
	}

	if (!is_wp_error($user_id))
	{

		$studenttable = array(
			'wp_usr_id' => intval($user_id),
			'parent_wp_usr_id' => intval($parent_id),
			'class_id' => isset($_POST['Class']) ? serialize(sanitize_price_array($_POST['Class'])) : '0',
			'class_date' => isset($_POST['Classdata']) ? serialize(sanitize_price_array($_POST['Classdata'])) : '0',
			's_rollno' => isset($_POST['s_rollno']) ? intval($_POST['s_rollno']) : '',
			's_fname' => $firstname,
			's_mname' => isset($_POST['s_mname']) ? sanitize_text_field($_POST['s_mname']) : '',
			's_lname' => isset($_POST['s_lname']) ? sanitize_text_field($_POST['s_lname']) : '',
			's_zipcode' => isset($_POST['s_zipcode']) ? intval($_POST['s_zipcode']) : '',
			's_country' => isset($_POST['s_country']) ? sanitize_text_field($_POST['s_country']) : '',
			's_gender' => isset($_POST['s_gender']) ? sanitize_text_field($_POST['s_gender']) : '',
			's_address' => isset($_POST['s_address']) ? sanitize_text_field($_POST['s_address']) : '',
			's_bloodgrp' => isset($_POST['s_bloodgrp']) ? sanitize_text_field($_POST['s_bloodgrp']) : '',
			's_dob' => isset($_POST['s_dob']) && !empty($_POST['s_dob']) ? wpsp_StoreDate(sanitize_text_field($_POST['s_dob'])) : '',
			's_doj' => isset($_POST['s_doj']) && !empty($_POST['s_doj']) ? wpsp_StoreDate(sanitize_text_field($_POST['s_doj'])) : '',
			's_phone' => isset($_POST['s_phone']) ? sanitize_text_field($_POST['s_phone']) : '',
			'p_fname' => $pfirstname,
			'p_mname' => $pmiddlename,
			'p_lname' => $plastname,
			'p_gender' => $pgender,
			'p_edu' => $pedu,
			'p_profession' => $pprofession,
			's_paddress' => isset($_POST['s_paddress']) ? sanitize_text_field($_POST['s_paddress']) : '',
			'p_bloodgrp' => $pbloodgroup,
			's_city' => isset($_POST['s_city']) ? sanitize_text_field($_POST['s_city']) : '',
			's_pcountry' => isset($_POST['s_pcountry']) ? sanitize_text_field($_POST['s_pcountry']) : '',
			's_pcity' => isset($_POST['s_pcity']) ? sanitize_text_field($_POST['s_pcity']) : '',
			's_pzipcode' => isset($_POST['s_pzipcode']) ? intval($_POST['s_pzipcode']) : '',
			'p_phone'  =>  isset($_POST['s_p_phone']) ? sanitize_text_field($_POST['s_p_phone']) : ''

		);

		$msg = 'Hello ' . sanitize_text_field($_POST['s_fname']);

		$msg.= '<br />Your are registered as student at <a href="' . esc_url(site_url()) . '">School</a><br /><br />';

		$msg.= 'Your Login details are below.<br />';

		$msg.= 'Your User Name is : ' . esc_html($username) . '<br />';

		$msg.= 'Your Password is : ' . sanitize_text_field($_POST['Password']) . '<br /><br />';

		$msg.= 'Please Login by clicking <a href="' . esc_url(site_url() . '/sch-dashboard').'">Here </a><br /><br />';

		$msg.= 'Thanks,<br />' . get_bloginfo('name');



		wpsp_send_mail($email, 'User Registered', $msg);
        $sp_stu_ins = $wpdb->insert($wpsp_student_table, $studenttable);
		$lastid = $wpdb->insert_id;
		do_action('wpsp_student_data_field',$lastid);

		$classidlist	=	array();
	    $classidc_sdate	=	array();

        if (isset($_POST['Class']) && !empty($_POST['Class'])) {
			foreach ($_POST['Class'] as $key => $value) {
				$classidlist[] = $value;
			}

			$classidc_sdate[] = wpsp_StoreDate('0000-00-00');
			//$classidc_sdate[] = $value;

			if(count($classidlist) == count($classidc_sdate)){
				$c = array_combine($classidlist, $classidc_sdate);
				// echo "<pre>";print_r($c);
				$classmapping = $wpdb->prefix . "wpsp_class_mapping";
				do_action('wpsp_student_data_field', $lastid);
				foreach ($c as $key => $value) {
					$wpdb->insert($classmapping, array("sid" => $lastid, "cid" => $key, "date" => wpsp_StoreDate($value)));
				}
			}
		}
		if ($sp_stu_ins){
			do_action('wpsp_student_created', $user_id, $studenttable);
		}

		// send registration mail
		wpsp_send_user_register_mail($userInfo, $user_id);

		if (!empty($_FILES['displaypicture']['name'])){

			$avatar = uploadImage('displaypicture');

			if (isset($avatar['url'])){

				update_user_meta($user_id, 'displaypicture', array(

					'full' => $avatar['url']

				));

				update_user_meta($user_id, 'simple_local_avatar', array(

					'full' => $avatar['url']

				));

			}

		}

		$msg = $sp_stu_ins ? esc_html("success", "wpschoolpress") : esc_html("Oops! Something went wrong try again.","wpschoolpress");

	}

	else

	if (is_wp_error($user_id))

	{

		$msg = $user_id->get_error_message();

	}

	echo wp_kses_post($msg);

	wp_die();

}


?>