export interface UserLoginInterface {
    email: string;
    password: string;
  }

export interface UserRegisterInterface {
    email: string;
    name: string;
    lastname: string; 
    rut: string;
    phone: string;
  }

export interface UserRecoveryInterface {
    email: string;
  }
  