import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { NewCarInterface } from '../intefaces/car';
import { environment } from 'src/environments/environment';
import { Storage } from '@ionic/storage-angular';

@Injectable({
  providedIn: 'root'
})
export class CarService {
  private API_URL = environment.API_URL_DEV;

  constructor(
    private http: HttpClient,
    private storageService: Storage,
  ) {
    storageService.create();
  }

  // Método para obtener las marcas de coches
  async getCarBrands(): Promise<string[]> {
    try {
      const headers = await this.getAuthHeaders(); // Obtener los headers con el token de autenticación
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return []; // Retornar un array vacío si no hay autorización
      }
  
      const response = await this.http.get<string[]>(`${this.API_URL}/jwt/cars/brands`, { headers }).toPromise();
  
      console.log('Marcas de coches obtenidas:', response);
      return response || []; // Asegurarte de que siempre retornas un array
    } catch (error) {
      console.error('Error al obtener las marcas de coches:', error);
      return []; // Retornar un array vacío en caso de error
    }
  }
  
  // Método para obtener los headers de autenticación
  public async getAuthHeaders() {
    const sessionData = await this.storageService.get('datos');
    const token = sessionData ? sessionData.token : null;  
    
    return new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
  }
}
