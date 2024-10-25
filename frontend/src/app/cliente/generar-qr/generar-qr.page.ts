import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular'; 

@Component({
  selector: 'app-generar-qr',
  templateUrl: './generar-qr.page.html',
  styleUrls: ['./generar-qr.page.scss'],
})
export class GenerarQrPage implements OnInit {

  constructor(
    private alertController: AlertController, // para mas adelante 
    private navCtrl: NavController,
    private storageService: Storage // para mas adelante 
  ) {}

  ngOnInit() {
  }
  goBack() {
    this.navCtrl.back();
  }

}