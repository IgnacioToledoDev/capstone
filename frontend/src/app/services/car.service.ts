
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
  async getCarBrands(): Promise<{ id: number, name: string }[]> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }
      const response = await this.http.get<any>(`${this.API_URL}/jwt/cars/brands/all`, { headers }).toPromise();
  
      if (response.success && response.data?.brands) {
        const brands = response.data.brands.map((brand: { id: number, name: string }) => ({
          id: brand.id,
          name: brand.name
        }));
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

  async getCarModelsByBrand(brandId: number): Promise<{ id: number, name: string }[]> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }
  
      const response = await this.http.get<any>(`${this.API_URL}/jwt/cars/models/all/${brandId}`, { headers }).toPromise();
  
      if (response.success && response.data?.models) {
        const models = response.data.models.map((model: { id: number, name: string }) => ({
          id: model.id,
          name: model.name
        }));
        return models;
      } else {
        console.error('Respuesta no válida al obtener los modelos de coches:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener los modelos de coches:', error);
      return [];
    }
  }
  
  

  async registerCar(car: NewCarInterface) {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        throw new Error('No se pudo recuperar el token de autenticación.');
      }
  
      console.log('Datos de registro a enviar:', car , headers);
      const response: any = await this.http.post(`${this.API_URL}/jwt/cars/create`, car, { headers }).toPromise();
      console.log('Registro car exitoso:', response);
  
      if (response.success) {
        const carData = response.data.car;
        const storedCar = {
          brand_id: carData.brand_id,
          model: carData.model,
          year: carData.year,
          patent: carData.patent,
          owner_id: carData.owner_id,
          id: carData.id,
          createdAt: carData.created_at,
          updatedAt: carData.updated_at
        };
        await this.storageService.set('newcar', storedCar);
        console.log('Datos del coche guardados en el Storage bajo "newcar":', storedCar);
      }
      return response;
  
    } catch (error) {
      console.error('Error en el registro del coche:', error);
      throw error;
    }
  }
  
  async getCars(): Promise<{ id: number; patent: string; brand: string; model: string; year: number }[]> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }
  
      const response = await this.http.get<any>(`${this.API_URL}/jwt/cars`, { headers }).toPromise();
  
      if (response.success && response.data?.cars) {
        const cars = response.data.cars.map((car: { id: number; patent: string; brand: string; model: string; year: number }) => ({
          id: car.id,
          patent: car.patent,
          brand: car.brand,
          model: car.model,
          year: car.year
        }));
        return cars;
      } else {
        console.error('Respuesta no válida al obtener los coches:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener los coches:', error);
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
