import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {StorageService} from "./storage.service";
import {environment} from "../../environments/environment";
import {ReservationInterface} from "../intefaces/reservation";

@Injectable({
  providedIn: 'root',
})
export class ReservationService {
  private API_URL = environment.API_URL_DEV;
  private isAuthenticated = false;

  constructor(
    private http: HttpClient,
    private storageService: StorageService
  ) {}

  // Crear una reserva
  async createReservation(reservation: ReservationInterface): Promise<boolean> {
    const headers = await this.getAuthHeaders();

    if (!headers.has('Authorization')) {
      console.error('No se pudo recuperar el token de autenticación.');
      return false;
    }

    try {
      const response: any = await this.http
        .post(`${this.API_URL}/jwt/reservation/create`, reservation, { headers })
        .toPromise();

      return !!response.success;
    } catch (error) {
      console.error('Error al crear la reserva:', error);
      return false;
    }
  }

  // Obtener todas las reservas por mecánico
  async getReservationsByMechanicId(mechanicId: number): Promise<any[]> {
    const headers = await this.getAuthHeaders();

    if (!headers.has('Authorization')) {
      console.error('No se pudo recuperar el token de autenticación.');
      return [];
    }

    try {
      const response: any = await this.http
        .get<any>(`${this.API_URL}/jwt/reservation/${mechanicId}/reservations`, {
          headers,
        })
        .toPromise();

      if (response && response.success && response.data) {
        console.log('Reservas obtenidas:', response.data);
        return response.data.reservations;
      } else {
        console.error('Respuesta no válida:', response);
        return [];
      }
    } catch (error) {
      console.error('Error al obtener las reservas:', error);
      return [];
    }
  }

  // Verificar si el usuario está autenticado
  async checkAuthenticated(): Promise<boolean> {
    const token = await this.storageService.get('datos');
    this.isAuthenticated = token !== null;
    await this.storageService.set('isAuthenticated', this.isAuthenticated);

    return this.isAuthenticated;
  }

  // Obtener los encabezados de autenticación
  private async getAuthHeaders(): Promise<HttpHeaders> {
    const sessionData = await this.storageService.get('datos');
    const token = sessionData ? sessionData.token : null;

    console.log('Token recuperado:', token);

    return new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
  }
}