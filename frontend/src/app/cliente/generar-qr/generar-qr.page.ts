import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { UserService } from "../../services/user.service";

@Component({
  selector: 'app-generar-qr',
  templateUrl: './generar-qr.page.html',
  styleUrls: ['./generar-qr.page.scss'],
})
export class GenerarQrPage implements OnInit {
  value: any;

  constructor(
    private alertController: AlertController, // para mas adelante
    private navCtrl: NavController,
    private userService: UserService,
    private storageService: Storage // para mas adelante
  ) { }



  ngOnInit() {
    this.userService.getClientInformation()
      .then(res => {
        console.log(res.data.user.email);
        this.value = res.data.user.email;

      })
      .catch(error => {
        console.error('Error al obtener la información del cliente:', error);
      });
  } 
  goBack() {
    this.navCtrl.back();
  }
}
