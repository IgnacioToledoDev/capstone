import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';

@Component({
  selector: 'app-qrinfo',
  templateUrl: './qrinfo.page.html',
  styleUrls: ['./qrinfo.page.scss'],
})
export class QrinfoPage implements OnInit {

  constructor(
    private navCtrl: NavController,

  ) {}

  ngOnInit() {
  }
  goBack() {
    this.navCtrl.back();
  }

}
