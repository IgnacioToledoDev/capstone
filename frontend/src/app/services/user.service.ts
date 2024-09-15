import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { UserLoginInterface, UserRegisterInterface } from '../intefaces/user';
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

  register(user: UserRegisterInterface) {
    return new Promise((resolve, reject) => {
      this.http.post(`${this.API_URL}/register`, user).subscribe(
        (res) => {
          resolve(res);
        },
        (err) => reject(err),
      );
    });
  }

  login(user: UserLoginInterface) {
    return new Promise((resolve, reject) => {
      this.http.post(`${this.API_URL}/login`, user).subscribe(
        (res) => {
          resolve(res);
        },
        (err) => reject(err),
      );
    });
  }

  async checkAuthenticated() {
    const token = await this.storageService.get('token');

    this.isAuthenticated = token !== null;
    await this.storageService.set('isAuthenticated', this.isAuthenticated);

    return this.isAuthenticated;
  }

  public async getAuthHeaders() {
    let token = await this.storageService.get('token');

    return new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
  }
}
