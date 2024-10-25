import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ManteHistoPage } from './mante-histo.page';

describe('ManteHistoPage', () => {
  let component: ManteHistoPage;
  let fixture: ComponentFixture<ManteHistoPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ManteHistoPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
