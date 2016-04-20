<?php
/**
 * @apiPrefix /users
 */
class UsersController extends Controller {
    
     /**
      * @middleware isAuthenticate
      * @api /me
      */
     function getMe() {
         $user = $this->get('user');
         print_r($user);
     }
     
     /**
      * @middleware isAuthenticate
      * @api GET /:id([0-9]+)
      */
     function selectUser($id) {
        echo "$id\n";
        print_r('getUser');
     }
     
     /**
      * @middleware isAuthenticate
      * @api POST /:id([0-9]+)
      */
     function updateUser($id) {
        print_r('updateUser');
     }
       
     /**
      * @api PUT /new
      */
     function createUser() {
        print_r('newUser');
     }

}