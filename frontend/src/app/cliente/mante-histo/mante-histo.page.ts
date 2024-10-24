import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

@Component({
  selector: 'app-mante-histo',
  templateUrl: './mante-histo.page.html',
  styleUrls: ['./mante-histo.page.scss'],
})
export class ManteHistoPage implements OnInit {

  eventos: { nombre: string, hora: string , patente: string, estado: string}[] = [
    { nombre: 'cambio de Oil', hora: '10:00 AM ' , patente:'KDTS82' ,estado:'21-9-2024'},
    { nombre: 'Emengencia', hora: '12:00 PM' , patente:'KDTS82',estado:'11-8-2023'},
    { nombre: 'Reparaciones', hora: '1:00 PM', patente:'KDTS82',estado:'1-4-2021' }
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