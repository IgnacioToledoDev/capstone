import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { Storage } from '@ionic/storage-angular';
import { CreateQuotationRequest , CreateQuotationResponse } from '../intefaces/catiza'; 


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

  async getCarServices(): Promise<{ id: number, name: string, description: string , type_id:number , price: number }[]> {
    try {
      const headers = await this.getAuthHeaders();

      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }

      const response = await this.http.get<any>(`${this.API_URL}/jwt/services/`, { headers }).toPromise();

      if (response.success && response.data?.services) {
        const services = response.data.services.map((service: { id: number, name: string, description: string, type_id:number  , price: number }) => ({
          id: service.id,
          name: service.name,
          description: service.description,
          type_id: service.type_id,
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

  async createQuotation(data: CreateQuotationRequest): Promise<CreateQuotationResponse | null | undefined> { 
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return null;
      }
  
      const response = await this.http.post<CreateQuotationResponse>(`${this.API_URL}/jwt/quotations/create`, data, { headers }).toPromise();
      console.log('Datos de quotations:', response);
  
      if (response && response.success) {
        console.log('Cotización creada exitosamente:', response);

        await this.storageService.set('Quotation', response);
        console.log('Cotización guardada en el Storage:', response);
  
        return response;
      } else {
        console.error('Error al crear la cotización:', response);
        return null;
      }
    } catch (error) {
      console.error('Error en el servicio al crear la cotización:', error);
      return null;
    }
  }

  async getQuotations(): Promise<any[]> {
    try {
      const headers = await this.getAuthHeaders();
      const response = await this.http.get<any>(`${this.API_URL}/jwt/quotations/`, { headers }).toPromise();
  
      if (response.success && response.data?.quotations) {
        return response.data.quotations; // Adjust this based on the actual response structure
      } else {
        console.error('Error en la respuesta al obtener las cotizaciones:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener las cotizaciones:', error);
      throw error; // Re-throw to handle it in the calling method
    }
  }

  async approveQuotation(quotationId: number): Promise<CreateQuotationResponse | null> {
    try {
      const headers = await this.getAuthHeaders();

      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return null;
      }

      const response = await this.http.patch<CreateQuotationResponse>(`${this.API_URL}/jwt/quotations/${quotationId}/approve`, 
        {}, 
        { headers }
      ).toPromise();

      if (response && response.success) {
        console.log('Cotización aprobada exitosamente:', response);
        return response;
      } else {
        console.error('Error al aprobar la cotización:', response);
        return null;
      }
    } catch (error) {
      console.error('Error en el servicio al aprobar la cotización:', error);
      return null;
    }
  }

  async declineQuotation(quotationId: number): Promise<CreateQuotationResponse | null> {
    try {
      const headers = await this.getAuthHeaders();

      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return null;
      }

      const response = await this.http.patch<CreateQuotationResponse>(`${this.API_URL}/jwt/quotations/${quotationId}/decline`, 
        {}, 
        { headers }
      ).toPromise();

      if (response && response.success) {
        console.log('Cotización rechazado exitosamente:', response);
        return response;
      } else {
        console.error('Error al rechazado la cotización:', response);
        return null;
      }
    } catch (error) {
      console.error('Error en el servicio al rechazado la cotización:', error);
      return null;
    }
  }

  async getQuotationsByMechanic(mechanicId: number): Promise<any[]> {
    try {
      const headers = await this.getAuthHeaders();

      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }

      const response = await this.http.get<any>(
        `${this.API_URL}/jwt/quotations/${mechanicId}/all`,
        { headers }
      ).toPromise();

      if (response.success && response.data?.quotations) {
        return response.data.quotations;
      } else {
        console.error('Error en la respuesta al obtener las cotizaciones del mecánico:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener las cotizaciones del mecánico:', error);
      return [];
    }
  }

  async getQuotationById(quotationId: number): Promise<any [] | null> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }
  
      const response = await this.http.get<any>(
        `${this.API_URL}/jwt/quotations/${quotationId}`,
        { headers }
      ).toPromise();
  
      if (response.success && response.data?.quotation) {
        return response.data.quotation; // Return the specific quotation from the response
      } else {
        console.error('Error en la respuesta al obtener la cotización:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener la cotización:', error);
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