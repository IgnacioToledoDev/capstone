import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { Storage } from '@ionic/storage-angular';
import { CalendarEntry , CurrentUser } from '../intefaces/car';



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

      if (response.success && response.data) {
        const calendar = response.data.calendar.map((entry: any) => ({
          id: entry.id,
          car_id: entry.car_id,
          start_maintenance: entry.start_maintenance,
          mechanic_id: entry.mechanic_id,
          description: entry.description
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
