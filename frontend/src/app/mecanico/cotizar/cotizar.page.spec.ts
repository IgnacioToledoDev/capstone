import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CotizarPage } from './cotizar.page';

describe('CotizarPage', () => {
  let component: CotizarPage;
  let fixture: ComponentFixture<CotizarPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(CotizarPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
