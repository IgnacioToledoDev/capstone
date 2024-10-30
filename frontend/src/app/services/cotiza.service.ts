import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { Storage } from '@ionic/storage-angular';

@Injectable({
  providedIn: 'root'
})
export class CotizaService {
  private API_URL = environment.API_URL_DEV;
  private isAuthenticated = false;

  constructor(
    private http: HttpClient,
    private storageService: Storage,
  ) {
    storageService.create();
  }

  async getCarServices(): Promise<{ id: number, name: string, description: string, price: number }[]> {
    try {
      const headers = await this.getAuthHeaders();

      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }

      const response = await this.http.get<any>(`${this.API_URL}/jwt/services/`, { headers }).toPromise();

      if (response.success && response.data?.services) {
        const services = response.data.services.map((service: { id: number, name: string, description: string, price: number }) => ({
          id: service.id,
          name: service.name,
          description: service.description,
          price: service.price
        }));
        return services;
      } else {
        console.error('Respuesta no válida al obtener los servicios de coches:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener los servicios de coches:', error);
      return [];
    }
  }

  async getServiceTypes(): Promise<{ id: number, name: string }[]> {
    try {
      const headers = await this.getAuthHeaders();

      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }

      const response = await this.http.get<any>(`${this.API_URL}/jwt/services/types`, { headers }).toPromise();

      if (response.success && response.data?.types) {
        const types = response.data.types.map((type: { id: number, name: string }) => ({
          id: type.id,
          name: type.name
        }));
        return types;
      } else {
        console.error('Respuesta no válida al obtener los tipos de servicios:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener los tipos de servicios:', error);
      return [];
    }
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
}
