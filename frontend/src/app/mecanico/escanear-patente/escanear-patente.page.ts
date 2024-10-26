import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular'; 

@Component({
  selector: 'app-escanear-patente',
  templateUrl: './escanear-patente.page.html',
  styleUrls: ['./escanear-patente.page.scss'],
})
export class EscanearPatentePage implements OnInit {

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