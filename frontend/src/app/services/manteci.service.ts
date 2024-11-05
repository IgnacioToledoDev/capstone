
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

  async getMaintenanceCalendar(): Promise<{ calendar: CalendarEntry[], current: CurrentUser[] }> {
    try {
      const headers = await this.getAuthHeaders();
  
      if (!headers.has('Authorization')) {
        console.error('No se pudo recuperar el token de autenticación.');
        return { calendar: [], current: [] };
      }
  
      const response = await this.http.get<any>(`${this.API_URL}/jwt/maintenance/calendar`, { headers }).toPromise();
      console.log('Registro car exitoso:', response);
  
      if (response.success && response.data) {
        const calendar = response.data.calendar.map((entry: any) => ({
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
  
        const current = response.data.current.map((user: any) => ({
          id: user.id,
          name: user.name,
          email: user.email
        }));
  
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

