import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { Storage } from '@ionic/storage-angular';
import { CalendarEntry , CurrentUser , HistoricalEntry  } from '../intefaces/car';

@Injectable({
  providedIn: 'root'
})
export class ManteciService {
  private API_URL = environment.API_URL_DEV;
  private isAuthenticated = false;

  constructor(
    private http: HttpClient,
    private storageService: Storage,
  ) {
    storageService.create();
  }

  async getMaintenanceCalendar(): Promise<{ calendar: CalendarEntry[], current: any[] }> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return { calendar: [], current: [] };
      }
  
      const response = await this.http.get<any>(`${this.API_URL}/jwt/maintenance/calendar`, { headers }).toPromise();
  
      // Log the full response for debugging
      console.log('API Response:', response);
  
      if (response && response.success && response.data) {
        // Convert calendarData to an array if it is an object with numeric keys
        const calendarData = response.data.calendar;
        console.log('Calendar Data (before transformation):', calendarData);
        
        const calendarArray = Array.isArray(calendarData)
          ? calendarData
          : Object.values(calendarData);  // Convert object to array if needed
        
        const calendar = calendarArray.map((entry: any) => ({
          id: entry.id,
          car_id: entry.car_id,
          start_maintenance: entry.start_maintenance,
          end_maintenance: entry.end_maintenance,
          mechanic_id: entry.mechanic_id,
          description: entry.name,
          pricing: entry.pricing,
          recommendation_action: entry.recommendation_action,
          status_id: entry.status_id,
          created_at: entry.created_at,
          updated_at: entry.updated_at
        }));
  
        // Log the transformed calendar array
        console.log('Transformed Calendar:', calendar);
  
        const current = Array.isArray(response.data.current) ? response.data.current : [];
  
        // Log the current data
        console.log('Current Data:', current);
  
        return { calendar, current };
      } else {
        console.error('Respuesta no válida al obtener el calendario de mantenimiento:', response);
        return { calendar: [], current: [] };
      }
    } catch (error) {
      console.error('Error al obtener el calendario de mantenimiento:', error);
      return { calendar: [], current: [] };
    }
  }
  
  
  

  async getMaintenanceHistorical(): Promise<HistoricalEntry[]> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return [];
      }
  
      const response = await this.http.get<any>(`${this.API_URL}/jwt/maintenance/historical`, { headers }).toPromise();
  
      if (response.success && response.data?.historical) {
        const historicalData = response.data.historical.map((entry: any) => ({
          id: entry.maintenance.id,
          name: entry.maintenance.name,
          status_id: entry.maintenance.status_id,
          recommendation_action: entry.maintenance.recommendation_action,
          pricing: entry.maintenance.pricing,
          car_id: entry.maintenance.car_id,
          mechanic_id: entry.maintenance.mechanic_id,
          start_maintenance: entry.maintenance.start_maintenance,
          end_maintenance: entry.maintenance.end_maintenance,
          car: {
            id: entry.car.id,
            patent: entry.car.patent,
            brand: entry.car.brand,
            model: entry.car.model,
            year: entry.car.year,
            fullName: entry.car.fullName,
          },
          owner: {
            id: entry.owner.id,
            name: entry.owner.name,
            email: entry.owner.email,
            phone: entry.owner.phone,
            username: entry.owner.username,
            lastname: entry.owner.lastname,
          }
        }));
        return historicalData;
      } else {
        console.error('Respuesta no válida al obtener el historial de mantenimiento:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener el historial de mantenimiento:', error);
      return [];
    }
  }

  async createMaintenanceRecord(maintenanceData: { carId: number; recommendation_action: string; services: { id: number }[]; startNow: boolean }): Promise<any> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return null;
      }
  
      const response = await this.http.post<any>(
        `${this.API_URL}/jwt/maintenance/create`,
        maintenanceData,
        { headers }
      ).toPromise();
  
      if (response.success) {
        console.log('Registro de mantenimiento exitoso:', response);
        
        // Store the maintenance ID in Ionic Storage
        const maintenanceId = response.data.maintenance.id;
        await this.storageService.set('idmantesion', maintenanceId);
        console.log('ID de mantenimiento guardado en Storage:', maintenanceId);
        
        return response;
      } else {
        console.error('Error en la respuesta del servidor:', response);
        return null;
      }
    } catch (error) {
      console.error('Error al crear el registro de mantenimiento:', error);
      return null;
    }
  }
  

  async getMaintenanceDetails(maintenanceId: number): Promise<any> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return null;
      }
  
      const response = await this.http.get<any>(`${this.API_URL}/jwt/maintenanceDetails/${maintenanceId}`, { headers }).toPromise();
  
      if (response.success && response.data) {
        console.log('Detalles de mantenimiento:', response);
        return response.data;
      } else {
        console.error('Respuesta no válida al obtener detalles de mantenimiento:', response);
        return null;
      }
    } catch (error) {
      console.error('Error al obtener detalles de mantenimiento:', error);
      return null;
    }
  }
  
  async updateMaintenanceStatusToNext(maintenanceId: number): Promise<any> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return null;
      }
  
      // Prepare the request body (add any necessary parameters if needed)
      const body = {}; // You can add any parameters if required for the status update.
  
      const response = await this.http.post<any>(
        `${this.API_URL}/jwt/maintenance/${maintenanceId}/status/next`,
        body,
        { headers }
      ).toPromise();
  
      if (response.success) {
        console.log('Estado de mantenimiento actualizado con éxito:', response);
        return response;
      } else {
        console.error('Error en la respuesta del servidor:', response);
        return null;
      }
    } catch (error) {
      console.error('Error al actualizar el estado de mantenimiento:', error);
      return null;
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

