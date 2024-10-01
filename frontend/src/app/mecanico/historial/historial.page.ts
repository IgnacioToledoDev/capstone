import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

@Component({
  selector: 'app-historial',
  templateUrl: './historial.page.html',
  styleUrls: ['./historial.page.scss'],
})
export class HistorialPage implements OnInit {

  eventos: { nombre: string, hora: string , patente: string}[] = [
    { nombre: 'jose herera', hora: '10:00 AM' , patente:'ABC-0834'},
    { nombre: 'isaac bravo', hora: '12:00 PM' , patente:'AAC-8634'},
    { nombre: 'Nacho jara', hora: '1:00 PM', patente:'AHG-6434' }
  ];

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController
  ) { }

  ngOnInit() {
  }
  
  goBack() {
    this.navCtrl.back();
  }

}
