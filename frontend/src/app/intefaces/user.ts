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

export interface UserResponse {
  success: boolean;
  data: {
    user: {
      id: number;
      name: string;
      email: string;
      phone: string | null;
      email_verified_at: string | null;
      password: string;
      rut: string | null;
      remember_token: string | null;
      created_at: string;
      updated_at: string;
      username: string;
      lastname: string;
      mechanic_score: number;
    };
    car_id: number;
  };
  message: string;
}
