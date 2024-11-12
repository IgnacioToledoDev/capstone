import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {StorageService} from "./storage.service";
import {environment} from "../../environments/environment";
import {ReservationInterface} from "../intefaces/reservation";

@Injectable({
  providedIn: 'root'
})
export class ReservationService {
  private API_URL = environment.API_URL_DEV;
  private isAuthenticated = false;

  constructor(
    private http: HttpClient,
    private storageService: StorageService,
  ) { }

  async createReservation(reservation: ReservationInterface) {
    const headers = await this.getAuthHeaders();

    if (!headers.has('Authorization')) {
      console.error('No se pudo recuperar el token de autenticación.');
      return [];
    }

    const response: any = await this.http.post(`${this.API_URL}/jwt/reservation/create`, reservation, {headers}).toPromise();

    return !!response.success;
  }

  async checkAuthenticated() {
    const token = await this.storageService.get('datos');
    this.isAuthenticated = token !== null;
    this.storageService.set('isAuthenticated', this.isAuthenticated);

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
