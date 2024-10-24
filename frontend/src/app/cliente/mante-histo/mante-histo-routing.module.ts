import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ManteHistoPage } from './mante-histo.page';

const routes: Routes = [
  {
    path: '',
    component: ManteHistoPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ManteHistoPageRoutingModule {}
