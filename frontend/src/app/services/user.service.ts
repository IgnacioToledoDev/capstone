import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { UserLoginInterface, UserRegisterInterface, UserRecoveryInterface, UserResponse } from '../intefaces/user';
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
    try {
      const headers = await this.getAuthHeaders();

      if (!headers.has('Authorization')) {
        throw new Error('No se pudo recuperar el token de autenticación.');
      }


      const response: any = await this.http.post(`${this.API_URL}/jwt/client/register`, user, { headers }).toPromise();
      console.log('Registro exitoso:', response);

      if (response.success) {
        const clientData = response.data.client;
        const sessionData = {
          user: {
            username: clientData.username,
            email: clientData.email,
            name: clientData.name,
            lastname: clientData.lastname,
            rut: clientData.rut,
            phone: clientData.phone,
            id: clientData.id,
            roles: clientData.roles,
          },
        };
        await this.storageService.set('newuser', sessionData.user);

        console.log('Registro exitoso. Datos guardados en el Storage bajo "newuser":', sessionData.user);
      }

      return response;

    } catch (error) {
      console.error('Error en el registro:', error);
      throw error;
    }
  }


  login(user: UserLoginInterface) {
    return new Promise((resolve, reject) => {
      this.http.post(`${this.API_URL}/users/login`, user).subscribe(
        async (res: any) => {
          const sessionData = {
            token: res.data.access_token,
            user: res.data.user,
            tokenType: res.data.token_type,
            expiresIn: res.data.expires_in,
            roles: res.data.user.roles
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

  async getClientInformation(): Promise<UserResponse> {
    const headers = await this.getAuthHeaders();

    if (!headers.has('Authorization')) {
      throw new Error('No se pudo recuperar el token de autenticación.');
    }

    return new Promise((resolve, reject) => {
      this.http.get<UserResponse>(`${this.API_URL}/jwt/client/information`, { headers })
        .subscribe(
          res => {
            resolve(res)
          },
          (err) => reject(err),
        )
    })
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
  }

  async getMechanics() {
    const headers = await this.getAuthHeaders();

    if (!headers.has('Authorization')) {
      throw new Error('No se pudo recuperar el token de autenticación.');
    }

    return new Promise((resolve, reject) => {
      this.http.get(`${this.API_URL}/jwt/mechanic/all`, { headers })
        .subscribe(
          res => {
            resolve(res)
          },
          (err) => reject(err),
        )
    })
  }


  async setMechanicScore(mechanicId: number, score: number, comment: string): Promise<any> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return null;
      }
  
      const body = {
        score: score,
        comment: comment,
      };
  
      const response = await this.http.post<any>(
        `${this.API_URL}/jwt/mechanic/${mechanicId}/setScore`,
        body,
        { headers }
      ).toPromise();
  
      if (response && response.success) {
        console.log('Calificación enviada con éxito:', response);
        return response;
      } else {
        console.error('Error en la respuesta del servidor al enviar la calificación:', response);
        return null;
      }
    } catch (error) {
      console.error('Error al enviar la calificación del mecánico:', error);
      return null;
    }
  }
  
}
