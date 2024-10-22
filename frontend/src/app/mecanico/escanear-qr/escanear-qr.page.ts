import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular'; 

@Component({
  selector: 'app-escanear-qr',
  templateUrl: './escanear-qr.page.html',
  styleUrls: ['./escanear-qr.page.scss'],
})
export class EscanearQrPage implements OnInit {

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