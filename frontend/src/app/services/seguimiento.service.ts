import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment'; // Asegúrate de que tienes la URL base en tu archivo environment.ts
import { Storage } from '@ionic/storage-angular';

@Injectable({
  providedIn: 'root'
})
export class SeguimientoService {
  private API_URL = environment.API_URL_DEV; // La URL base para la API

  constructor(
    private http: HttpClient,
    private storageService: Storage
  ) {
    storageService.create();
  }

  // Método para obtener el usuario por RUT
  async getUserByRut(rut: string): Promise<any> {
    try {
      // Obtenemos los headers de autenticación
      const headers = await this.getAuthHeaders();

      // Verificamos si el header de Authorization está presente
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return null;
      }

      // Realizamos la petición GET al endpoint con el RUT como parámetro
      const response = await this.http.get<any>(`${this.API_URL}/jwt/client/${rut}/find`, { headers }).toPromise();

      // Verificamos la respuesta
      if (response.success && response.data) {
        // Guardamos los datos del usuario en el Storage
        await this.storageService.set('userDataQR', response.data);

        // Mostramos los datos en la consola
        console.log('Datos del usuario:', response.data);

        // Regresamos los datos del usuario encontrados
        return response.data;
      } else {
        console.error('Error al obtener el usuario por RUT:', response);
        return null;
      }
    } catch (error) {
      console.error('Error al obtener el usuario por RUT:', error);
      return null;
    }
  }

  // Método para obtener los encabezados de autenticación
  private async getAuthHeaders() {
    const sessionData = await this.storageService.get('datos');
    const token = sessionData ? sessionData.token : null;

    console.log('Token recuperado:', token);

    return new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
  }
}
