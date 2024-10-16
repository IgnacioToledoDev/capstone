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
      const headers = await this.getAuthHeaders();
    
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return []; 
      }
    
      // Realizamos la solicitud para obtener la respuesta completa
      const response = await this.http.get<any>(`${this.API_URL}/jwt/cars/brands/all`, { headers }).toPromise();
    
      if (response.success && response.data?.brands) {
        // Extraemos los nombres de las marcas del array de objetos
        const brands = response.data.brands.map((brand: { id: number, name: string }) => brand.name);
        return brands;
      } else {
        console.error('Respuesta no válida al obtener las marcas de coches:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener las marcas de coches:', error);
      return []; 
    }
  }
  
  
 
  public async getAuthHeaders() {
    const sessionData = await this.storageService.get('datos');
    const token = sessionData ? sessionData.token : null;  
    
    return new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
  }
}
