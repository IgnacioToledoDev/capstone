import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { UserLoginInterface, UserRegisterInterface ,UserRecoveryInterface} from '../intefaces/user';
import { environment } from 'src/environments/environment';
import { Storage } from '@ionic/storage-angular';

@Injectable({
  providedIn: 'root',
})
export class UserService {
  private API_URL = environment.API_URL_DEV;
  private isAuthenticated = false;

  constructor(
    private http: HttpClient,
    private storageService: Storage,
  ) {
    storageService.create();
  }


  async register(user: UserRegisterInterface) {
    const headers = await this.getAuthHeaders();
    return new Promise((resolve, reject) => {
      this.http.post(`${this.API_URL}/users/client/register`, user, { headers }).subscribe(
        (res) => {
          console.log('Registro exitoso:', res);
          resolve(res);
        },
        (err) => reject(err),
      );
    });
  }

  login(user: UserLoginInterface) {
    return new Promise((resolve, reject) => {
      this.http.post(`${this.API_URL}/users/login`, user).subscribe(
        async (res: any) => {
          const sessionData = {
            token: res.data.access_token,
            user: res.data.user,
            tokenType: res.data.token_type,
            expiresIn: res.data.expires_in
          };
          await this.storageService.set('datos', sessionData);
          await this.storageService.set('token', sessionData.token);  
  
          console.log('Inicio de sesión exitoso. Datos guardados en el Storage:', sessionData);
  
          resolve(res);
        },
        (err) => reject(err),
      );
    });
  }
  
  
  async getUserSession() {
    const sessionData = await this.storageService.get('datos');
    console.log('Datos recuperados del Storage:', sessionData);
    return sessionData;
  }

  recovery(user: UserRecoveryInterface) {
    return new Promise((resolve, reject) => {
      this.http.post(`${this.API_URL}/users/recovery`, user).subscribe(
        (res) => {
          console.log('recuperar exitoso:', res);
          resolve(res);
        },
        (err) => reject(err),
      );
    });
  }

  async checkAuthenticated() {
    const token = await this.storageService.get('datos');

    this.isAuthenticated = token !== null;
    await this.storageService.set('isAuthenticated', this.isAuthenticated);

    return this.isAuthenticated;
  }

  public async getAuthHeaders() {
    const sessionData = await this.storageService.get('datos');
    const token = sessionData ? sessionData.token : null;  
  
    console.log('Token recuperado:', token);  
  
    return new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
  }}
