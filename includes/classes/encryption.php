<?php
  class Encryption
  {
      
      public static function encrypt_data($data, $algorithm, $key)
      {
          $e_module=mcrypt_module_open($algorithm, '','ecb','');
          $key=substr($key,0,mcrypt_enc_get_key_size($e_module));
          $iv_size=mcrypt_enc_get_iv_size($e_module);
          $iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
          
            /* Initialize encryption handle */
            if (mcrypt_generic_init($e_module, $key, $iv) != -1) {

                /* Encrypt data */
                $enc_text= mcrypt_generic($e_module, $data);
                mcrypt_generic_deinit($e_module);
                mcrypt_module_close($e_module);   
                return $enc_text;

                
            }   
            
            return false;

          
      }
      
      public static function decrypt_data($data, $algorithm, $key)
      {
          $e_module=mcrypt_module_open($algorithm, '','ecb','');
          $key=substr($key,0,mcrypt_enc_get_key_size($e_module));
          $iv_size=mcrypt_enc_get_iv_size($e_module);
          $iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
          
          /* Initialize encryption handle */
          if (mcrypt_generic_init($e_module, $key, $iv) != -1) {
          
            /* Reinitialize buffers for decryption */
                mcrypt_generic_init($e_module, $key, $iv);
                $dec_text = mdecrypt_generic($e_module, $data);

                /* Clean up */
                mcrypt_generic_deinit($e_module);
                mcrypt_module_close($e_module);
                
                return $dec_text;
          }
          
          return false;
          
      }
  }
?>
