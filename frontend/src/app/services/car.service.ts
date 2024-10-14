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

  async newcar(car: NewCarInterface) {
    const headers = await this.getAuthHeaders();
    return new Promise((resolve, reject) => {
      this.http.post(`${this.API_URL}/jwt/cars/create`, car, { headers }).subscribe(
        (res) => {
          console.log('Registro exitoso:', res);
          resolve(res);
        },
        (err) => reject(err),
      );
    });
  }
  public async getAuthHeaders() {
    const sessionData = await this.storageService.get('datos');
    const token = sessionData ? sessionData.token : null;  
  
    console.log('Token recuperado:', token);  
  
    return new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
  }}
