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

  newcar(car: NewCarInterface) {
    return new Promise((resolve, reject) => {
      this.http.post(`${this.API_URL}/jwt/cars/create`, car).subscribe(
        (res) => {
          console.log('Registro exitoso:', res);
          resolve(res);
        },
        (err) => reject(err),
      );
    });
  }
}
