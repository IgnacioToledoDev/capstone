export interface UserLoginInterface {
    email: string;
    password: string;
  }

export interface UserRegisterInterface {
    username: string;
    email: string;
    password: string;
    c_password?: string;
  }
  