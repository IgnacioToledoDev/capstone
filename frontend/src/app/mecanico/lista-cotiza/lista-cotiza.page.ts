import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

@Component({
  selector: 'app-lista-cotiza',
  templateUrl: './lista-cotiza.page.html',
  styleUrls: ['./lista-cotiza.page.scss'],
})
export class ListaCotizaPage implements OnInit {

  eventos: { nombre: string, hora: string , patente: string, estado: string}[] = [
    { nombre: 'jose herera', hora: '10:00 AM' , patente:'ABC-0834' ,estado:'⁈'},
    { nombre: 'isaac bravo', hora: '12:00 PM' , patente:'AAC-8634',estado:'✓'},
    { nombre: 'Nacho jara', hora: '1:00 PM', patente:'AHG-6434',estado:'✕' }
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
