import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { InfoSerCliPage } from './info-ser-cli.page';

const routes: Routes = [
  {
    path: '',
    component: InfoSerCliPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class InfoSerCliPageRoutingModule {}
