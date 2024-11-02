import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { ManteciService } from 'src/app/services/manteci.service'; 

@Component({
  selector: 'app-reserva',
  templateUrl: './reserva.page.html',
  styleUrls: ['./reserva.page.scss'],
})
export class ReservaPage implements OnInit {


  eventos: { nombre: string, marca: string , patente: string, modelo: string}[] = [
    { nombre: 'jose herera', marca: 'TOYOTA' , patente:'ABC-0834' ,modelo:'G54'},
    { nombre: 'isaac bravo', marca: 'BMW' , patente:'AAC-8634',modelo:'A10'},
    { nombre: 'Nacho jara', marca: 'NISAN', patente:'AHG-6434',modelo:'2011' }
  ];

  constructor(
    private navCtrl: NavController
  ) { }

  ngOnInit() {
  }
  
  goBack() {
    this.navCtrl.back();
  }

}