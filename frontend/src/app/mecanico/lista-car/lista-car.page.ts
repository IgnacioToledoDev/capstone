import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

@Component({
  selector: 'app-lista-car',
  templateUrl: './lista-car.page.html',
  styleUrls: ['./lista-car.page.scss'],
})
export class ListaCarPage implements OnInit {

  eventos: { nombre: string, marca: string , patente: string, modelo: string}[] = [
    { nombre: 'jose herera', marca: 'TOYOTA' , patente:'ABC-0834' ,modelo:'G54'},
    { nombre: 'isaac bravo', marca: 'BMW' , patente:'AAC-8634',modelo:'A10'},
    { nombre: 'Nacho jara', marca: 'NISAN', patente:'AHG-6434',modelo:'2011' }
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
